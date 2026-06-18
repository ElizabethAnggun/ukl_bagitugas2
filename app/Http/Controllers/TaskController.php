<?php

namespace App\Http\Controllers; // ✅ FIX

use App\Http\Controllers\Controller; // ✅ TAMBAHKAN INI
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Friend;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * TaskController - Controller untuk manajemen tugas
 * Menangani CRUD tugas dan update status
 */
class TaskController extends Controller
{
    /**
     * Tampilkan daftar semua tugas
     */
    public function index()
    {
        $user = Auth::user();
        
        // KUNCI UTAMA: Hanya ambil tugas yang ditugaskan kepada user yang sedang login
        $tasks = Task::where('user_id', $user->id)
            ->with(['project', 'user'])
            ->latest()
            ->get();

        // Ambil proyek untuk keperluan dropdown/filter jika ada di halaman
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->distinct()
            ->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }
    /**
     * Tampilkan form tambah tugas baru
     */
    public function create()
    {
        $user = Auth::user();
        
        // Ambil proyek milik user yang login
        $projects = Project::where('user_id', $user->id)->get();

        // Ambil daftar teman menggunakan sender_id dan receiver_id sesuai tabelmu
        $friends = Friend::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($friendship) use ($user) {
                // Pastikan relasi di model Friend.php kamu juga menyesuaikan ya (sender/receiver)
                // Jika error property not found, ganti ->receiver dan ->sender ke ->friend dan ->user sesuai nama relasimu.
                return $friendship->sender_id === $user->id
                    ? $friendship->receiver 
                    : $friendship->sender;
            })->filter(); // filter() untuk membuang nilai null jika ada data yang tidak sempurna

        // Masukkan akun sendiri (kevin kok vibecoding) ke pilihan paling atas
        $friends->prepend($user);

        return view('tasks.create', compact('projects', 'friends'));
    }

    /**
     * Simpan tugas baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'email' => 'required|email|exists:users,email',
            'deadline' => 'required|date',
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ], [
            'title.required' => 'Judul tugas wajib diisi',
            'project_id.required' => 'Proyek wajib dipilih',
            'email.required' => 'Email penerima wajib diisi',
            'email.exists' => 'User dengan email tersebut tidak terdaftar di sistem',
            'deadline.required' => 'Deadline wajib diisi',
        ]);

        $user = Auth::user();
        $assignee = User::where('email', $validated['email'])->first();

        // 1. Otorisasi
        $project = Project::find($validated['project_id']);
        if (!$project->isManager($user->id)) {
            return redirect()->back()->with('error', 'Hanya pemilik atau sub pengelola proyek yang dapat menambah tugas.');
        }

        // 2. Validasi Pertemanan (Bypass jika assign ke diri sendiri)
        if ($assignee->id !== $user->id) {
            if (!$user->isFriendsWith($assignee->id)) {
                return redirect()->back()->withInput()->with('error', 'Anda hanya dapat memberikan tugas kepada user yang sudah menjadi teman.');
            }
        }

        // 3. Simpan tugas (Ini sudah berfungsi dengan baik)
        $task = Task::create([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'],
            'user_id' => $assignee->id,
            'deadline' => $validated['deadline'],
            'status' => $validated['status'],
        ]);

        // 4. KUNCI PERBAIKAN: Logika Notifikasi yang Terpisah
        if ($assignee->id !== $user->id) {
            // Jika untuk teman, kirim notifikasi penugasan
            Notification::create([
                'user_id' => $assignee->id,
                'title' => 'Tugas Baru Ditugaskan',
                'message' => "Anda telah diberikan tugas baru: \"{$task->title}\" dalam proyek \"{$project->name}\" oleh {$user->name}.",
                'link' => route('tasks.show', $task->id),
                'is_read' => false,
            ]);
        } else {
            // Jika untuk DIRI SENDIRI, tetap kirim notifikasi konfirmasi!
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Tugas Pribadi Berhasil Dibuat',
                'message' => "Anda menugaskan diri sendiri untuk: \"{$task->title}\" di proyek \"{$project->name}\".",
                'link' => route('tasks.show', $task->id),
                'is_read' => false,
            ]);
        }

        // Opsional: Redirect langsung ke halaman detail proyek agar tugasnya langsung terlihat!
        // Jika kamu ingin kembali ke daftar tugas, biarkan seperti semula: route('tasks.index')
        return redirect()->route('projects.show', $project->id)->with('success', 'Tugas berhasil dibuat!');
    }
    /**
     * Tampilkan detail tugas
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['project.user', 'project.managers', 'user', 'activityLogs', 'comments.user']);

        // Ambil daftar anggota proyek (Owner + semua user yang punya tugas di proyek ini)
        $owner = $task->project->user;
        $assignedUsers = User::whereHas('tasks', function($query) use ($task) {
            $query->where('project_id', $task->project_id);
        })->get();

        $projectMembers = collect([$owner])->concat($assignedUsers)->unique('id');
        
        // Tambahkan informasi apakah user adalah manager (Owner/Sub Pengelola)
        $isManager = $task->project->isManager(auth()->id());

        return view('tasks.show', compact('task', 'projectMembers', 'isManager'));
    }

    /**
     * Tampilkan form edit tugas
     */
    public function edit(Task $task)
    {
        // Pastikan hanya owner project yang bisa edit tugas
        $this->authorize('update', $task);

        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)->get();

        // Ambil daftar teman menggunakan sender_id dan receiver_id
        $friends = Friend::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($friendship) use ($user) {
                return $friendship->sender_id === $user->id
                    ? $friendship->receiver
                    : $friendship->sender;
            })->filter();

        // Agar saat mengedit tugas, nama sendiri tetap muncul
        $friends->prepend($user);

        return view('tasks.edit', compact('task', 'projects', 'friends'));
    }

    /**
     * Update tugas di database
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'email' => 'required|email|exists:users,email',
            'deadline' => 'required|date',
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ]);

        $user = Auth::user();
        $assignee = User::where('email', $validated['email'])->first();

        // Validasi Pertemanan (jika assignee berubah)
        if ($assignee->id !== $user->id && $assignee->id !== $task->user_id) {
            if (!$user->isFriendsWith($assignee->id)) {
                return redirect()->back()->withInput()->with('error', 'Anda hanya dapat memberikan tugas kepada user yang sudah menjadi teman.');
            }
        }

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        if ($newStatus === 'selesai' && !$task->completed_at) {
            $validated['completed_at'] = now();
        }

        // Update data (Ganti email input ke user_id)
        $updateData = $validated;
        $updateData['user_id'] = $assignee->id;
        unset($updateData['email']);

        $oldAssigneeId = $task->user_id;
        $task->update($updateData);

        // Jika penerima tugas berubah, kirim notifikasi ke penerima baru
        if ($assignee->id !== $oldAssigneeId && $assignee->id !== $user->id) {
            Notification::create([
                'user_id' => $assignee->id,
                'title' => 'Tugas Ditugaskan ke Anda',
                'message' => "Tugas \"{$task->title}\" dalam proyek \"{$task->project->name}\" kini ditugaskan kepada Anda oleh {$user->name}.",
                'link' => route('tasks.show', $task->id),
                'is_read' => false,
            ]);
        }

        // Jika status berubah menjadi selesai, buat log (cek jika terlambat)
        if ($oldStatus !== $newStatus && $newStatus === 'selesai') {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => $task->isLate() ? 'terlambat' : 'selesai',
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Hapus tugas dari database
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Update status tugas via AJAX (untuk dashboard interaktif)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('changeStatus', $task);

        $request->validate([
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ]);

        $oldStatus = $task->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];
        if ($newStatus === 'selesai' && !$task->completed_at) {
            $updateData['completed_at'] = now();
        }

        $task->update($updateData);

        // Buat log jika status selesai (cek jika terlambat)
        if ($oldStatus !== $newStatus && $newStatus === 'selesai') {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => $task->isLate() ? 'terlambat' : 'selesai',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status tugas diperbarui',
            'status' => $newStatus,
            'status_label' => $task->status_label,
            'status_color' => $task->status_color,
        ]);
    }

    /**
     * Upload bukti pengerjaan tugas
     */
    public function uploadProof(Request $request, Task $task)
    {
        // Otorisasi: Hanya user yang ditugaskan atau pemilik proyek
        $this->authorize('changeStatus', $task);

        $request->validate([
            'proof_files' => 'required|array|max:5',
            'proof_files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,mp4,mov,avi,mkv|max:51200', // Max 50MB for video support
        ], [
            'proof_files.required' => 'Pilih setidaknya satu file',
            'proof_files.*.mimes' => 'Format file harus: jpg, jpeg, png, pdf, doc, docx, mp4, mov, avi, atau mkv',
            'proof_files.*.max' => 'Ukuran file maksimal 50MB',
        ]);

        $files = $task->proof_file ?? [];

        if ($request->hasFile('proof_files')) {
            foreach ($request->file('proof_files') as $file) {
                $path = $file->store('proofs', 'public');
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }
        }

        $task->update([
            'proof_file' => $files,
            'status' => 'selesai',
            'completed_at' => $task->completed_at ?? now(),
        ]);

        // Buat log aktivitas jika status berubah
        ActivityLog::create([
            'user_id' => Auth::id(),
            'task_id' => $task->id,
            'activity' => $task->title,
            'status' => $task->isLate() ? 'terlambat' : 'selesai',
        ]);

        return redirect()->back()->with('success', 'Bukti pengerjaan berhasil diunggah!');
    }

    /**
     * Download bukti pengerjaan (Force download)
     */
    public function downloadProof(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        
        $path = $request->path;
        $name = $request->name;

        if (!Storage::disk('public')->exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($path, $name);
    }

    /**
     * Hapus bukti pengerjaan
     */
    public function deleteProof(Request $request, Task $task)
    {
        $this->authorize('changeStatus', $task);
        
        $pathToDelete = $request->path;
        $files = $task->proof_file ?? [];
        
        $newFiles = array_filter($files, function($file) use ($pathToDelete) {
            if ($file['path'] === $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                return false;
            }
            return true;
        });

        $task->update([
            'proof_file' => array_values($newFiles)
        ]);

        return redirect()->back()->with('success', 'Bukti pengerjaan berhasil dihapus!');
    }
}

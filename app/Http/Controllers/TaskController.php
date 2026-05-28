<?php

namespace App\Http\Controllers; // ✅ FIX

use App\Http\Controllers\Controller; // ✅ TAMBAHKAN INI
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
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
        
        // Ambil tugas:
        // 1. Dari proyek milik user yang login
        // 2. ATAU tugas yang ditugaskan langsung ke user yang login
        // Ini memastikan user hanya melihat daftar tugas dari proyek yang mereka ikuti
        $tasks = Task::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('project', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })
        ->with(['project', 'user'])
        ->latest()
        ->get();

        // Ambil semua proyek di mana user terlibat untuk filter
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
        
        // Ambil proyek di mana user terlibat (Owner atau yang ditugaskan di tugas apa pun dalam proyek tersebut)
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->distinct()
            ->get();
        
        // Ambil semua user untuk dropdown "ditugaskan ke"
        $users = User::all();

        return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Simpan tugas baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ], [
            'title.required' => 'Judul tugas wajib diisi',
            'project_id.required' => 'Proyek wajib dipilih',
            'user_id.required' => 'User yang ditugaskan wajib dipilih',
            'deadline.required' => 'Deadline wajib diisi',
        ]);

        // Buat tugas baru
        $task = Task::create($validated);

        // Jika status selesai, buat log aktivitas (cek jika terlambat)
        if ($task->status === 'selesai') {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => $task->isLate() ? 'terlambat' : 'selesai',
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Tampilkan detail tugas
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['project.user', 'user', 'activityLogs', 'comments.user']);

        // Ambil daftar anggota proyek (Owner + semua user yang punya tugas di proyek ini)
        $owner = $task->project->user;
        $assignedUsers = User::whereHas('tasks', function($query) use ($task) {
            $query->where('project_id', $task->project_id);
        })->get();

        $projectMembers = collect([$owner])->concat($assignedUsers)->unique('id');

        return view('tasks.show', compact('task', 'projectMembers'));
    }

    /**
     * Tampilkan form edit tugas
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $user = Auth::user();
        
        // Ambil proyek di mana user terlibat (Owner atau yang ditugaskan di tugas apa pun dalam proyek tersebut)
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->distinct()
            ->get();
            
        $users = User::all();

        return view('tasks.edit', compact('task', 'projects', 'users'));
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
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'status' => 'required|in:belum_mulai,berjalan,selesai',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        if ($newStatus === 'selesai' && !$task->completed_at) {
            $validated['completed_at'] = now();
        }

        // Update tugas
        $task->update($validated);

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
        $this->authorize('update', $task);

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
        $this->authorize('update', $task);

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
        $this->authorize('update', $task);
        
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

<?php

namespace App\Http\Controllers; // ✅ FIX

use App\Http\Controllers\Controller; // ✅ TAMBAHKAN INI
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Ambil tugas dari proyek milik user yang login
        $tasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['project', 'user'])
        ->latest()
        ->get();

        // Update status terlambat otomatis
        foreach ($tasks as $task) {
            $task->updateStatusIfLate();
        }

        // Ambil semua proyek untuk filter
        $projects = Project::where('user_id', $user->id)->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    /**
     * Tampilkan form tambah tugas baru
     */
    public function create()
    {
        $user = Auth::user();
        
        // Ambil proyek milik user
        $projects = Project::where('user_id', $user->id)->get();
        
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
            'status' => 'required|in:belum_mulai,berjalan,selesai,terlambat',
        ], [
            'title.required' => 'Judul tugas wajib diisi',
            'project_id.required' => 'Proyek wajib dipilih',
            'user_id.required' => 'User yang ditugaskan wajib dipilih',
            'deadline.required' => 'Deadline wajib diisi',
        ]);

        // Buat tugas baru
        $task = Task::create($validated);

        // Jika status selesai, buat log aktivitas
        if ($task->status === 'selesai') {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => 'selesai',
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

        $task->load(['project', 'user', 'activityLogs']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Tampilkan form edit tugas
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $user = Auth::user();
        $projects = Project::where('user_id', $user->id)->get();
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
            'status' => 'required|in:belum_mulai,berjalan,selesai,terlambat',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        // Update tugas
        $task->update($validated);

        // Jika status berubah menjadi selesai atau terlambat, buat log
        if ($oldStatus !== $newStatus && in_array($newStatus, ['selesai', 'terlambat'])) {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => $newStatus,
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
            'status' => 'required|in:belum_mulai,berjalan,selesai,terlambat',
        ]);

        $oldStatus = $task->status;
        $newStatus = $request->status;

        $task->update(['status' => $newStatus]);

        // Buat log jika status selesai atau terlambat
        if ($oldStatus !== $newStatus && in_array($newStatus, ['selesai', 'terlambat'])) {
            ActivityLog::create([
                'user_id' => $task->user_id,
                'task_id' => $task->id,
                'activity' => $task->title,
                'status' => $newStatus,
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
}

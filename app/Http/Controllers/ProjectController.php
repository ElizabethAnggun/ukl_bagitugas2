<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ProjectController - Controller untuk manajemen proyek
 * Menangani CRUD proyek
 */
class ProjectController extends Controller
{
    /**
     * Tampilkan daftar semua proyek
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil proyek milik user yang login
        $projects = Project::where('user_id', $user->id)
            ->withCount('tasks')
            ->latest()
            ->get();

        // Hitung progress untuk setiap proyek
        foreach ($projects as $project) {
            $project->progress = $project->getProgressAttribute();
            $project->completed_tasks = $project->tasks()->where('status', 'selesai')->count();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Tampilkan form tambah proyek baru
     */
    public function create()
    {
        // Ambil semua user untuk dropdown "assign to"
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    /**
     * Simpan proyek baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
        ], [
            'name.required' => 'Nama proyek wajib diisi',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'deadline.required' => 'Deadline wajib diisi',
            'deadline.after_or_equal' => 'Deadline harus setelah atau sama dengan tanggal mulai',
        ]);

        // Tambahkan user_id dari user yang login
        $validated['user_id'] = Auth::id();

        // Buat proyek baru
        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil dibuat!');
    }

    /**
     * Tampilkan detail proyek
     */
    public function show(Project $project)
    {
        // Cek apakah user berhak melihat proyek ini
        $this->authorize('view', $project);

        // Load relasi tasks dengan user
        $project->load(['tasks.user', 'tasks' => function ($query) {
            $query->latest();
        }]);

        // Hitung progress
        $project->progress = $project->getProgressAttribute();
        $project->completed_tasks = $project->tasks()->where('status', 'selesai')->count();

        return view('projects.show', compact('project'));
    }

    /**
     * Tampilkan form edit proyek
     */
    public function edit(Project $project)
    {
        // Cek apakah user berhak mengedit proyek ini
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update proyek di database
     */
    public function update(Request $request, Project $project)
    {
        // Cek apakah user berhak mengupdate proyek ini
        $this->authorize('update', $project);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date|after_or_equal:start_date',
        ], [
            'name.required' => 'Nama proyek wajib diisi',
            'deadline.after_or_equal' => 'Deadline harus setelah atau sama dengan tanggal mulai',
        ]);

        // Update proyek
        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Hapus proyek dari database
     */
    public function destroy(Project $project)
    {
        // Cek apakah user berhak menghapus proyek ini
        $this->authorize('delete', $project);

        // Hapus proyek (tasks otomatis terhapus karena onDelete cascade)
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyek berhasil dihapus!');
    }
}

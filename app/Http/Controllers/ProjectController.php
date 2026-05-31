<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Menampilkan semua proyek
     */
    public function index()
    {
        $user = Auth::user();

        // Hanya menampilkan proyek di mana user adalah owner atau anggota (punya tugas)
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Menampilkan form tambah proyek
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Menyimpan proyek baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'deadline'    => 'required|date|after_or_equal:start_date',
        ]);

        // Tambahkan user login
        $validated['user_id'] = auth()->id();

        Project::create($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil dibuat');
    }

    /**
     * Menampilkan detail proyek
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        
        $user = Auth::user();
        $isOwner = $project->user_id === $user->id;

        // Load relasi tugas dan managers
        $project->load(['tasks.user', 'user', 'managers']);

        // Ambil daftar teman untuk autocomplete/pilihan sub pengelola
        $friendIds = \App\Models\Friend::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function($f) use ($user) {
                return $f->sender_id === $user->id ? $f->receiver_id : $f->sender_id;
            });

        $friends = \App\Models\User::whereIn('id', $friendIds)
            ->where('id', '!=', $project->user_id)
            ->get();

        // Hitung statistik Proyek (untuk Owner)
        $stats = null;
        if ($isOwner) {
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->where('status', 'selesai')->count();
            
            $stats = [
                'total' => $totalTasks,
                'belum_mulai' => $project->tasks->where('status', 'belum_mulai')->count(),
                'berjalan' => $project->tasks->where('status', 'berjalan')->count(),
                'selesai' => $completedTasks,
                'terlambat' => $project->tasks->filter->isLate()->count(),
                'progress' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0
            ];
        }

        // Filter tugas untuk User biasa (hanya tugas miliknya)
        $tasks = $isOwner || $project->managers->contains($user->id) ? $project->tasks : $project->tasks->where('user_id', $user->id);

        return view('projects.show', compact('project', 'tasks', 'isOwner', 'stats', 'friends'));
    }

    /**
     * Menampilkan form edit proyek
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update proyek
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'deadline'    => 'required|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil diperbarui');
    }

    /**
     * Hapus proyek
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyek berhasil dihapus');
    }

    /**
     * Tambahkan sub pengelola ke proyek
     */
    public function addManager(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $userToAdd = User::where('email', $validated['email'])->first();

        // 1. Cek apakah user adalah owner
        if ($userToAdd->id === $project->user_id) {
            return redirect()->back()->with('error', 'User ini adalah pemilik proyek.');
        }

        // 2. Cek apakah sudah jadi manager
        if ($project->managers()->where('user_id', $userToAdd->id)->exists()) {
            return redirect()->back()->with('error', 'User ini sudah menjadi sub pengelola.');
        }

        // 3. Tambahkan manager
        $project->managers()->attach($userToAdd->id);

        // 4. Kirim notifikasi
        Notification::create([
            'user_id' => $userToAdd->id,
            'title' => 'Anda Menjadi Sub Pengelola',
            'message' => "Anda telah dijadikan sub pengelola untuk proyek \"{$project->name}\" oleh " . auth()->user()->name . ".",
            'link' => route('projects.show', $project->id),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sub pengelola berhasil ditambahkan.');
    }

    /**
     * Hapus sub pengelola dari proyek
     */
    public function removeManager(Project $project, User $user)
    {
        $this->authorize('update', $project);

        $project->managers()->detach($user->id);

        return redirect()->back()->with('success', 'Sub pengelola berhasil dihapus.');
    }
}
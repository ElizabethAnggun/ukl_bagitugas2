<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Menampilkan semua proyek
     */
    public function index()
    {
        $projects = Project::with('user')->latest()->get();

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
    public function show($id)
    {
        $project = Project::with(['user', 'tasks'])->findOrFail($id);

        return view('projects.show', compact('project'));
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
}
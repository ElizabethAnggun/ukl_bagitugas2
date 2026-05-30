<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Mengambil project di mana user adalah owner atau anggota
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'deadline' => 'required|date',
            'user_id' => 'required|exists:users,id', // Harus ada user_id pemilik[cite: 2]
        ]);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Proyek berhasil dibuat',
            'data' => $project
        ], 201);
    }

    public function show($id)
    {
        $project = Project::with(['user', 'tasks'])->find($id);[cite: 6]

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        // Menambahkan atribut progress buatanmu ke dalam JSON[cite: 6]
        return response()->json([
            'data' => $project,
            'stats' => [
                'progress' => $project->progress . '%',
                'total_tasks' => $project->total_tasks
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $project->update($request->all());[cite: 6]

        return response()->json([
            'message' => 'Proyek berhasil diperbarui',
            'data' => $project
        ], 200);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $project->delete();
        return response()->json(['message' => 'Proyek berhasil dihapus'], 200);
    }
}
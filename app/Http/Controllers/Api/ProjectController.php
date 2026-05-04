<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('user')->get();
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
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'message' => 'Proyek berhasil dibuat',
            'data' => $project
        ], 201);
    }

    public function show($id)
    {
        $project = Project::with(['user', 'tasks'])->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

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

        $project->update($request->all());

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
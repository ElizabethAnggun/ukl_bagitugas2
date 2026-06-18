<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskAPIController extends Controller
{
    public function index() // Atau sesuaikan dengan nama fungsi aslimu
    {
        $user = Auth::user();

        // FILTER KETAT: Sama seperti di TaskController, wajib 'user_id'
        $tasks = Task::where('user_id', $user->id)
            ->with(['project', 'user'])
            ->latest()
            ->get();

        // Hitung statistik HANYA berdasarkan tugas pribadi user
        $stats = [
            'total' => $tasks->count(),
            'berjalan' => $tasks->where('status', 'berjalan')->count(),
            'selesai' => $tasks->where('status', 'selesai')->count(),
            'terlambat' => $tasks->filter->isLate()->count(),
        ];

        // Format data untuk dikirim ke JSON...
        $formattedTasks = $tasks->map(function($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project_name' => $task->project ? $task->project->name : 'Proyek Dihapus',
                'project_id' => $task->project_id, // Pastikan ini tetap ada
                'user_name' => $task->user->name,
                'user_avatar' => substr($task->user->name, 0, 1),
                'deadline' => $task->deadline->format('d M Y'),
                'is_late' => $task->isLate(),
                'status' => $task->status,
                'status_label' => ucwords(str_replace('_', ' ', $task->status)),
                'status_color' => $task->status == 'berjalan' ? 'text-blue-600 bg-blue-50' : ($task->status == 'selesai' ? 'text-emerald-600 bg-emerald-50' : 'text-gray-600 bg-gray-100'),
                'can_change_status' => Auth::user()->can('changeStatus', $task),
                'can_update' => Auth::user()->can('update', $task),
                'can_delete' => Auth::user()->can('delete', $task),
                'show_url' => route('tasks.show', $task->id),
                'edit_url' => route('tasks.edit', $task->id),
                'delete_url' => route('tasks.destroy', $task->id),
                'csrf_token' => csrf_token(),
            ];
        });

        return response()->json([
            'stats' => $stats,
            'tasks' => $formattedTasks
        ]);
    }

    public function show($id)
    {
        $task = Task::with(['project', 'user', 'comments.user'])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail tugas berhasil diambil',
            'data' => $task
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'status' => 'required|string'
        ]);

        $task = Task::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat',
            'data' => $task
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'project_id' => 'sometimes|exists:projects,id',
            'user_id' => 'sometimes|exists:users,id',
            'deadline' => 'sometimes|date',
            'status' => 'sometimes|string'
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus'
        ]);
    }
}
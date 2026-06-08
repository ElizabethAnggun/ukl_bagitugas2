<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskAPIController extends Controller
{
    /**
     * Menampilkan daftar semua tugas dalam format JSON
     */
    public function index()
    {
        $tasks = Task::with(['project', 'user'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tugas berhasil diambil',
            'data' => $tasks
        ], 200);
    }

    /**
     * Menampilkan detail satu tugas berdasarkan ID
     */
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
}

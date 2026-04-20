<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController - Controller untuk dashboard
 * Menampilkan statistik dan ringkasan data
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard dengan statistik
     */
    public function index()
    {
        $user = Auth::user();

        // Hitung statistik
        $totalProjects = Project::where('user_id', $user->id)->count();
        $totalTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
        $completedTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'selesai')->count();
        
        $lateTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'terlambat')->count();

        // Ambil proyek dengan progress
        $projects = Project::where('user_id', $user->id)
            ->withCount('tasks')
            ->with(['tasks' => function ($query) {
                $query->where('status', 'selesai');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Hitung progress untuk setiap proyek
        foreach ($projects as $project) {
            $project->progress = $project->getProgressAttribute();
        }

        // Ambil tugas terbaru
        $recentTasks = Task::whereHas('project', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['project', 'user'])
        ->latest()
        ->take(5)
        ->get();

        // Ambil aktivitas terbaru
        $activities = ActivityLog::where('user_id', $user->id)
            ->with(['task', 'task.project'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalProjects',
            'totalTasks',
            'completedTasks',
            'lateTasks',
            'projects',
            'recentTasks',
            'activities'
        ));
    }
}

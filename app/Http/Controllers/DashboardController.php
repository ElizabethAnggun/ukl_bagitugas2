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

        // Hitung statistik (Proyek di mana user terlibat)
        $projectQuery = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->distinct();

        $totalProjects = $projectQuery->count();
        
        $taskQuery = Task::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhereHas('project', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        });

        $totalTasks = (clone $taskQuery)->count();
        $completedTasks = (clone $taskQuery)->where('status', 'selesai')->count();
        
        // Hitung tugas terlambat
        $lateTasks = (clone $taskQuery)->get()->filter->isLate()->count();

        // Ambil proyek dengan progress
        $projects = (clone $projectQuery)
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
        $recentTasks = (clone $taskQuery)
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

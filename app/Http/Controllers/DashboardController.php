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

        // Hitung statistik Proyek di mana user terlibat (Owner atau yang ditugaskan)
        $projectQuery = Project::where('user_id', $user->id)
            ->orWhereHas('tasks', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->distinct();

        $totalProjects = $projectQuery->count();
        
        // HANYA AMBIL TUGAS YANG DITUGASKAN KE USER LOGIN (Tujuan Dashboard Baru)
        $taskQuery = Task::where('user_id', $user->id);

        $totalTasks = (clone $taskQuery)->count();
        $runningTasks = (clone $taskQuery)->where('status', 'berjalan')->count();
        $completedTasks = (clone $taskQuery)->where('status', 'selesai')->count();
        
        // Hitung tugas terlambat (tugas pribadi)
        $lateTasks = (clone $taskQuery)->get()->filter->isLate()->count();

        // Ambil proyek dengan progress (tetap tampilkan proyek yang diikuti)
        $projects = $projectQuery
            ->withCount('tasks')
            ->with(['tasks' => function ($query) {
                $query->where('status', 'selesai');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Ambil tugas terbaru (Hanya tugas saya)
        $recentTasks = (clone $taskQuery)
            ->with(['project', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Ambil riwayat aktivitas terbaru
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->with('task')
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalProjects', 
            'totalTasks', 
            'runningTasks', 
            'completedTasks', 
            'lateTasks', 
            'projects', 
            'recentTasks',
            'recentActivities'
        ));
    }
}

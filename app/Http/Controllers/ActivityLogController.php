<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * ActivityLogController - Controller untuk riwayat aktivitas
 * Menampilkan log aktivitas tugas
 */
class ActivityLogController extends Controller
{
    /**
     * Tampilkan daftar aktivitas
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil aktivitas milik user yang login
        $activities = ActivityLog::where('user_id', $user->id)
            ->with(['task', 'task.project'])
            ->latest()
            ->paginate(20);

        return view('activities.index', compact('activities'));
    }
}

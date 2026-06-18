<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeController extends Controller
{
    /**
     * Data live untuk Dashboard
     */
    public function dashboardLive()
    {
        $user = Auth::user();
        $taskQuery = Task::where('user_id', $user->id);

        $totalTasks = (clone $taskQuery)->count();
        $runningTasks = (clone $taskQuery)->where('status', 'berjalan')->count();
        $completedTasks = (clone $taskQuery)->where('status', 'selesai')->count();
        $lateTasks = (clone $taskQuery)->get()->filter->isLate()->count();

        // Ambil tugas terbaru untuk update list
        $recentTasks = (clone $taskQuery)
            ->with(['project'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'project_name' => $task->project->name,
                    'status' => $task->status,
                    'status_label' => $task->status_label,
                    'status_color' => $task->status_color,
                    'created_at_human' => $task->created_at->diffForHumans(),
                    'url' => route('tasks.show', $task->id)
                ];
            });

        return response()->json([
            'stats' => [
                'total' => $totalTasks,
                'running' => $runningTasks,
                'completed' => $completedTasks,
                'late' => $lateTasks
            ],
            'recent_tasks' => $recentTasks
        ]);
    }

    /**
     * Data live untuk Detail Proyek
     */
    public function projectLive(Project $project)
    {
        $user = Auth::user();
        $isOwner = $project->user_id === $user->id;
        $isManager = $project->isManager($user->id);

        $tasks = $isManager 
            ? $project->tasks()->with('user')->get() 
            : $project->tasks()->where('user_id', $user->id)->with('user')->get();
        
        $stats = null;
        if ($isManager) {
            $allTasks = $project->tasks;
            $totalTasks = $allTasks->count();
            $completedTasks = $allTasks->where('status', 'selesai')->count();
            
            $stats = [
                'total' => $totalTasks,
                'belum_mulai' => $allTasks->where('status', 'belum_mulai')->count(),
                'berjalan' => $allTasks->where('status', 'berjalan')->count(),
                'selesai' => $completedTasks,
                'terlambat' => $allTasks->filter->isLate()->count(),
                'progress' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0
            ];
        }

        $taskData = $tasks->map(function($task) use ($user) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'user_name' => $task->user->name,
                'user_avatar' => substr($task->user->name, 0, 1),
                'deadline' => $task->deadline->format('d M Y'),
                'status' => $task->status,
                'status_label' => $task->status_label,
                'status_color' => $task->status_color,
                'is_late' => $task->isLate(),
                'can_update' => $user->can('update', $task),
                'can_delete' => $user->can('delete', $task),
                'can_change_status' => $user->can('changeStatus', $task),
                'show_url' => route('tasks.show', $task->id),
                'edit_url' => route('tasks.edit', $task->id),
                'delete_url' => route('tasks.destroy', $task->id),
                'csrf_token' => csrf_token(),
            ];
        });

        return response()->json([
            'stats' => $stats,
            'tasks' => $taskData
        ]);
    }

    /**
     * Data live untuk Detail Tugas (Komentar, Bukti, Status)
     */
    public function taskLive(Task $task)
    {
        $user = Auth::user();
        $task->load(['comments.user', 'project']);
        
        $comments = $task->comments->map(function($comment) use ($task, $user) {
            return [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'user_id' => $comment->user_id,
                'comment' => $comment->comment,
                'is_manager' => $task->project->isManager($comment->user_id),
                'avatar' => substr($comment->user->name, 0, 1),
                'created_at_human' => $comment->created_at->diffForHumans(),
                'is_edited' => $comment->updated_at && $comment->updated_at->ne($comment->created_at),
                'can_edit' => $comment->user_id === $user->id,
                'can_delete' => $comment->user_id === $user->id,
                'update_url' => route('comments.update', $comment->id),
                'delete_url' => route('comments.destroy', $comment->id),
            ];
        });

        return response()->json([
            'id' => $task->id,
            'status' => $task->status,
            'status_label' => $task->status_label,
            'status_color' => $task->status_color,
            'is_late' => $task->isLate(),
            'comments' => $comments,
            'proofs' => $task->proof_file ?? [],
            'project_owner_id' => $task->project->user_id,
            'task_user_id' => $task->user_id,
            'is_manager' => $task->project->isManager($user->id),
            'auth_id' => $user->id,
            'csrf_token' => csrf_token(),
        ]);
    }

    /**
     * Data live untuk Daftar Tugas Utama
     */
    public function tasksLive()
    {
        $user = Auth::user();

        // KUNCI PERBAIKAN: Tambahkan where('user_id', $user->id)
        $tasks = Task::where('user_id', $user->id)
            ->with(['project', 'user']) // Pastikan relasinya tetap dipanggil
            ->latest()
            ->get();

        $stats = [
            'total' => $tasks->count(),
            'berjalan' => $tasks->where('status', 'berjalan')->count(),
            'selesai' => $tasks->where('status', 'selesai')->count(),
            'terlambat' => $tasks->filter->isLate()->count(),
        ];

        $taskData = $tasks->map(function($task) use ($user) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'project_name' => $task->project->name,
                'user_name' => $task->user->name,
                'user_avatar' => substr($task->user->name, 0, 1),
                'deadline' => $task->deadline->format('d M Y'),
                'status' => $task->status,
                'status_label' => $task->status_label,
                'status_color' => $task->status_color,
                'is_late' => $task->isLate(),
                'can_update' => $user->can('update', $task),
                'can_delete' => $user->can('delete', $task),
                'can_change_status' => $user->can('changeStatus', $task),
                'show_url' => route('tasks.show', $task->id),
                'edit_url' => route('tasks.edit', $task->id),
                'delete_url' => route('tasks.destroy', $task->id),
                'csrf_token' => csrf_token(),
            ];
        });

        return response()->json([
            'stats' => $stats,
            'tasks' => $taskData
        ]);
    }

    /**
     * Data live untuk Jejaring Teman
     */
    public function friendsLive()
    {
        $user = Auth::user();

        // 1. Teman yang sudah diterima (Accepted)
        $friendships = Friend::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver'])
            ->get();

        $friends = $friendships->map(function($f) use ($user) {
            $friend = $f->sender_id === $user->id ? $f->receiver : $f->sender;
            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'email' => $friend->email,
                'avatar' => substr($friend->name, 0, 1),
                'unfriend_url' => route('friends.unfriend', $friend->id),
            ];
        });

        // 2. Permintaan Masuk (Pending untuk saya sebagai penerima)
        $pendingRequests = Friend::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with('sender')
            ->get()
            ->map(function($r) {
                return [
                    'id' => $r->id,
                    'sender_name' => $r->sender->name,
                    'sender_email' => $r->sender->email,
                    'sender_avatar' => substr($r->sender->name, 0, 1),
                    'accept_url' => route('friends.accept', $r->id),
                    'reject_url' => route('friends.reject', $r->id),
                ];
            });

        // 3. Menunggu Konfirmasi (Pending yang saya kirim)
        $sentRequests = Friend::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->with('receiver')
            ->get()
            ->map(function($r) {
                return [
                    'id' => $r->id,
                    'receiver_name' => $r->receiver->name,
                    'receiver_email' => $r->receiver->email,
                    'receiver_avatar' => substr($r->receiver->name, 0, 1),
                ];
            });

        return response()->json([
            'friends' => $friends,
            'pending_requests' => $pendingRequests,
            'sent_requests' => $sentRequests,
            'csrf_token' => csrf_token(),
        ]);
    }

    /**
     * Polling Notifikasi (Lengkap untuk halaman index)
     */
    public function notificationsLive()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();
        $pendingFriendsCount = Friend::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $notificationData = $notifications->map(function($n) {
            return [
                'id' => $n->id,
                'title' => $n->title ?? 'Pembaruan Sistem',
                'message' => $n->message,
                'is_read' => (bool)$n->is_read,
                'created_at_human' => $n->created_at->diffForHumans(),
                'url' => route('notifications.read', $n->id),
            ];
        });

        return response()->json([
            'count' => $unreadCount,
            'pending_friends_count' => $pendingFriendsCount,
            'notifications' => $notificationData,
            'total_count' => $notifications->count()
        ]);
    }
}

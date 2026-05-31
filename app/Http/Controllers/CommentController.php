<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Simpan komentar baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|max:1000',
        ], [
            'task_id.required' => 'ID Tugas wajib disertakan',
            'task_id.exists' => 'Tugas tidak ditemukan',
            'comment.required' => 'Komentar tidak boleh kosong',
            'comment.max' => 'Komentar maksimal 1000 karakter',
        ]);

        $user = Auth::user();
        $task = Task::with('project')->find($request->task_id);

        // Buat komentar
        $comment = Comment::create([
            'task_id' => $request->task_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        // --- NOTIFIKASI ---
        $mentionRecipients = collect();
        $regularRecipients = collect();

        // 1. Pemilik Proyek & Sub Pengelola (jika bukan pengomentar)
        if (!$task->project->isManager($user->id)) {
            // Notif ke Owner
            $regularRecipients->push($task->project->user_id);
            
            // Notif ke Sub Pengelola
            foreach ($task->project->managers as $manager) {
                if ($manager->id !== $user->id) {
                    $regularRecipients->push($manager->id);
                }
            }
        } else {
            // Jika yang komen adalah Manager/Owner, notif ke Manager lainnya
            if ($task->project->user_id !== $user->id) {
                $regularRecipients->push($task->project->user_id);
            }
            foreach ($task->project->managers as $manager) {
                if ($manager->id !== $user->id) {
                    $regularRecipients->push($manager->id);
                }
            }
        }

        // 2. SEMUA user yang terlibat dalam proyek ini (siapapun yang punya tugas di proyek ini)
        // Ini memastikan hanya user proyek tersebut yang dapat notif
        $projectMembers = Task::where('project_id', $task->project_id)
            ->where('user_id', '!=', $user->id)
            ->pluck('user_id');
        
        foreach ($projectMembers as $memberId) {
            $regularRecipients->push($memberId);
        }

        // 3. Deteksi User yang dimention (@nama atau @email)
        // HANYA kirim notifikasi mention jika user tersebut memang anggota proyek
        preg_match_all('/@([\w\.\@\-]+)/', $request->comment, $mentions);
        if (!empty($mentions[1])) {
            foreach ($mentions[1] as $mention) {
                $mentionedUser = User::where('name', $mention)
                    ->orWhere('email', $mention)
                    ->first();
                
                if ($mentionedUser && $mentionedUser->id !== $user->id) {
                    // Cek apakah user yang dimention adalah anggota proyek (Owner, Sub Pengelola, atau punya tugas di situ)
                    $isProjectMember = $task->project->isManager($mentionedUser->id) || 
                                     Task::where('project_id', $task->project_id)
                                         ->where('user_id', $mentionedUser->id)
                                         ->exists();

                    if ($isProjectMember) {
                        $mentionRecipients->push($mentionedUser->id);
                    }
                }
            }
        }

        // Filter: User yang dimention tidak perlu dapat notifikasi reguler
        $mentionRecipients = $mentionRecipients->unique();
        $regularRecipients = $regularRecipients->unique()->diff($mentionRecipients);

        // Kirim notifikasi MENTION
        foreach ($mentionRecipients as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'title' => 'Mention Baru',
                'message' => '@' . $user->name . ' menyebut anda dalam komentar pada tugas: ' . $task->title,
                'link' => route('tasks.show', $task->id),
            ]);
        }

        // Kirim notifikasi REGULER
        foreach ($regularRecipients as $recipientId) {
            Notification::create([
                'user_id' => $recipientId,
                'title' => 'Komentar Baru',
                'message' => $user->name . ' mengomentari tugas: ' . $task->title,
                'link' => route('tasks.show', $task->id),
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Hapus komentar
     */
    public function destroy(Comment $comment)
    {
        // Pastikan hanya pemilik komentar yang bisa menghapus
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}

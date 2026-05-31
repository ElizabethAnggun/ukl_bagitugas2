<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

/**
 * TaskPolicy - Policy untuk otorisasi tugas
 * Menentukan apa yang boleh dilakukan user terhadap tugas
 */
class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Hanya anggota proyek (Owner atau yang punya tugas di proyek tersebut) yang bisa melihat detail
     */
    public function view(User $user, Task $task): bool
    {
        // Cek apakah user adalah owner atau sub pengelola proyek
        if ($task->project->isManager($user->id)) {
            return true;
        }

        // Cek apakah user memiliki tugas apa pun di proyek yang sama
        return $task->project->tasks()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Hanya pemilik proyek/sub pengelola yang bisa edit (ganti judul, deadline, dll)
     */
    public function update(User $user, Task $task): bool
    {
        return $task->project->isManager($user->id);
    }

    /**
     * Determine whether the user can change the status of the task.
     * Pemilik, Sub Pengelola, dan User yang ditugaskan bisa ubah status
     */
    public function changeStatus(User $user, Task $task): bool
    {
        return $task->project->isManager($user->id) || $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya pemilik proyek/sub pengelola yang bisa hapus
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->project->isManager($user->id);
    }
}

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
        // Cek apakah user adalah owner proyek
        if ($user->id === $task->project->user_id) {
            return true;
        }

        // Cek apakah user memiliki tugas apa pun di proyek yang sama
        return \App\Models\Task::where('project_id', $task->project_id)
            ->where('user_id', $user->id)
            ->exists();
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
     * Hanya pemilik proyek (yang ngasih tugas) dan user yang ditugaskan yang bisa edit
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id || $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya pemilik proyek (yang ngasih tugas) dan user yang ditugaskan yang bisa hapus
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id || $user->id === $task->user_id;
    }
}

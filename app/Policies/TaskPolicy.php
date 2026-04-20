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
     * User bisa melihat tugas dari proyek miliknya
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
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
     * User bisa mengedit tugas dari proyek miliknya
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * User bisa menghapus tugas dari proyek miliknya
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id;
    }
}

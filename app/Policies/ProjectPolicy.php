<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/**
 * ProjectPolicy - Policy untuk otorisasi proyek
 * Menentukan apa yang boleh dilakukan user terhadap proyek
 */
class ProjectPolicy
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
     * User bisa melihat proyek jika dia Owner proyek ATAU memiliki tugas di proyek tersebut
     */
    public function view(User $user, Project $project): bool
    {
        // Cek jika owner
        if ($user->id === $project->user_id) {
            return true;
        }

        // Cek jika memiliki tugas di proyek ini
        return $project->tasks()->where('user_id', $user->id)->exists();
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
     * User hanya bisa mengedit proyek miliknya sendiri
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * User hanya bisa menghapus proyek miliknya sendiri
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}

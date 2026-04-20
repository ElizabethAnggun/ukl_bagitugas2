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
     * User hanya bisa melihat proyek miliknya sendiri
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
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

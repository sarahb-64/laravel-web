<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function manageProjects(User $user)
    {
        // Verifica si el usuario tiene el rol de admin
        return $user->hasRole('admin');
    }

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('view-projects');
    }

    public function view(User $user, Project $project)
    {
        return $user->hasRole('admin') || $project->user_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('create-projects');
    }

    public function update(User $user, Project $project)
    {
        return $user->hasRole('admin') || $project->user_id === $user->id;
    }

    public function delete(User $user, Project $project)
    {
        return $user->hasRole('admin') || $project->user_id === $user->id;
    }
}
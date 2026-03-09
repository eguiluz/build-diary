<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function uploadFiles(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function manageDiary(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function manageLinks(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}

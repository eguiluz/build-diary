<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProjectFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProjectFilePolicy
{
    use HandlesAuthorization;

    public function view(User $user, ProjectFile $file): bool
    {
        return $user->id === $file->project->user_id;
    }

    public function delete(User $user, ProjectFile $file): bool
    {
        return $user->id === $file->project->user_id;
    }

    public function download(User $user, ProjectFile $file): bool
    {
        return $user->id === $file->project->user_id;
    }
}

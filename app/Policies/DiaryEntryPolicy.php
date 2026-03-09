<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DiaryEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DiaryEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DiaryEntry $entry): bool
    {
        return $user->id === $entry->project->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, DiaryEntry $entry): bool
    {
        return $user->id === $entry->project->user_id;
    }

    public function delete(User $user, DiaryEntry $entry): bool
    {
        return $user->id === $entry->project->user_id;
    }
}

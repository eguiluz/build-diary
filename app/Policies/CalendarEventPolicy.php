<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class CalendarEventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CalendarEvent $event): bool
    {
        return $user->id === $event->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CalendarEvent $event): bool
    {
        return $user->id === $event->user_id;
    }

    public function delete(User $user, CalendarEvent $event): bool
    {
        return $user->id === $event->user_id;
    }
}

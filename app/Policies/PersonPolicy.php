<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class PersonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Person $person): bool
    {
        return $user->id === $person->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Person $person): bool
    {
        return $user->id === $person->user_id;
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->id === $person->user_id;
    }

    public function restore(User $user, Person $person): bool
    {
        return $user->id === $person->user_id;
    }

    public function forceDelete(User $user, Person $person): bool
    {
        return $user->id === $person->user_id;
    }
}

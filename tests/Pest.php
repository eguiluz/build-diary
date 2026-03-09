<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function asUser(?User $user = null): User
{
    $user ??= User::factory()->create();
    actingAs($user);

    return $user;
}

<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = asUser();
});

it('can view people list', function () {
    Person::factory()->forUser(test()->user)->count(3)->create();

    getJson(route('people.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can create a person', function () {
    $data = [
        'name' => 'Juan García',
        'email' => 'juan@example.com',
        'birthday' => '1990-05-15',
        'birthday_reminder' => true,
        'reminder_days_before' => 7,
    ];

    postJson(route('people.store'), $data)
        ->assertCreated()
        ->assertJsonPath('name', 'Juan García');

    assertDatabaseHas('people', [
        'user_id' => test()->user->id,
        'name' => 'Juan García',
    ]);
});

it('can view own person', function () {
    $person = Person::factory()->forUser(test()->user)->create();

    getJson(route('people.show', $person))
        ->assertOk()
        ->assertJsonPath('id', $person->id);
});

it('cannot view another user\'s person', function () {
    $otherUser = User::factory()->create();
    $person = Person::factory()->forUser($otherUser)->create();

    getJson(route('people.show', $person))
        ->assertForbidden();
});

it('can update a person', function () {
    $person = Person::factory()->forUser(test()->user)->create();

    $data = [
        'name' => 'Nombre Actualizado',
        'email' => 'nuevo@example.com',
    ];

    putJson(route('people.update', $person), $data)
        ->assertOk()
        ->assertJsonPath('name', 'Nombre Actualizado');
});

it('can delete a person', function () {
    $person = Person::factory()->forUser(test()->user)->create();

    deleteJson(route('people.destroy', $person))
        ->assertNoContent();

    assertSoftDeleted('people', ['id' => $person->id]);
});

it('requires name to create a person', function () {
    $data = ['email' => 'test@example.com'];

    postJson(route('people.store'), $data)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

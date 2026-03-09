<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = asUser();
});

it('can view tags list', function () {
    Tag::factory()->forUser(test()->user)->count(5)->create();

    getJson(route('tags.index'))
        ->assertOk()
        ->assertJsonCount(5);
});

it('can create a tag', function () {
    $data = [
        'name' => 'Urgente',
        'color' => '#EF4444',
    ];

    postJson(route('tags.store'), $data)
        ->assertCreated()
        ->assertJsonPath('name', 'Urgente')
        ->assertJsonPath('slug', 'urgente');

    assertDatabaseHas('tags', [
        'user_id' => test()->user->id,
        'name' => 'Urgente',
    ]);
});

it('can update a tag', function () {
    $tag = Tag::factory()->forUser(test()->user)->create();

    $data = [
        'name' => 'Nombre Actualizado',
        'color' => '#10B981',
    ];

    putJson(route('tags.update', $tag), $data)
        ->assertOk()
        ->assertJsonPath('name', 'Nombre Actualizado');
});

it('can delete a tag', function () {
    $tag = Tag::factory()->forUser(test()->user)->create();

    deleteJson(route('tags.destroy', $tag))
        ->assertNoContent();

    assertDatabaseMissing('tags', ['id' => $tag->id]);
});

it('requires unique tag name per user', function () {
    Tag::factory()->forUser(test()->user)->create(['name' => 'Duplicado']);

    $data = [
        'name' => 'Duplicado',
        'color' => '#3B82F6',
    ];

    postJson(route('tags.store'), $data)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

it('allows different users to have the same tag name', function () {
    $otherUser = User::factory()->create();
    Tag::factory()->forUser($otherUser)->create(['name' => 'Compartido']);

    $data = [
        'name' => 'Compartido',
        'color' => '#3B82F6',
    ];

    postJson(route('tags.store'), $data)
        ->assertCreated();
});

it('cannot update another user\'s tag', function () {
    $otherUser = User::factory()->create();
    $tag = Tag::factory()->forUser($otherUser)->create();

    $data = ['name' => 'Intento de hackeo'];

    putJson(route('tags.update', $tag), $data)
        ->assertForbidden();
});

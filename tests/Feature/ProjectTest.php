<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = asUser();
    test()->projectStatus = ProjectStatus::factory()->default()->create();
});

it('can view projects list', function () {
    Project::factory()
        ->forUser(test()->user)
        ->count(3)
        ->create(['status_id' => test()->projectStatus->id]);

    getJson(route('projects.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can create a project', function () {
    $data = [
        'title' => 'Mi nuevo proyecto DIY',
        'description' => 'Descripción del proyecto',
        'category' => 'carpintería',
        'status_id' => test()->projectStatus->id,
    ];

    postJson(route('projects.store'), $data)
        ->assertCreated()
        ->assertJsonPath('title', 'Mi nuevo proyecto DIY');

    assertDatabaseHas('projects', [
        'user_id' => test()->user->id,
        'title' => 'Mi nuevo proyecto DIY',
    ]);
});

it('can view own project', function () {
    $project = Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => test()->projectStatus->id]);

    getJson(route('projects.show', $project))
        ->assertOk()
        ->assertJsonPath('id', $project->id);
});

it('cannot view another user\'s project', function () {
    $otherUser = User::factory()->create();
    $project = Project::factory()
        ->forUser($otherUser)
        ->create(['status_id' => test()->projectStatus->id]);

    getJson(route('projects.show', $project))
        ->assertForbidden();
});

it('can update a project', function () {
    $project = Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => test()->projectStatus->id]);

    $data = [
        'title' => 'Título actualizado',
        'description' => 'Nueva descripción',
        'status_id' => test()->projectStatus->id,
    ];

    putJson(route('projects.update', $project), $data)
        ->assertOk()
        ->assertJsonPath('title', 'Título actualizado');
});

it('can delete a project', function () {
    $project = Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => test()->projectStatus->id]);

    deleteJson(route('projects.destroy', $project))
        ->assertNoContent();

    assertSoftDeleted('projects', ['id' => $project->id]);
});

it('can archive a project', function () {
    $project = Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => test()->projectStatus->id]);

    postJson(route('projects.archive', $project))
        ->assertOk();

    assertDatabaseHas('projects', [
        'id' => $project->id,
        'is_archived' => true,
    ]);
});

it('requires title to create a project', function () {
    $data = ['description' => 'Descripción sin título'];

    postJson(route('projects.store'), $data)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

it('can filter projects by status', function () {
    $otherStatus = ProjectStatus::factory()->create();

    Project::factory()
        ->forUser(test()->user)
        ->count(2)
        ->create(['status_id' => test()->projectStatus->id]);

    Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => $otherStatus->id]);

    getJson(route('projects.index', ['status' => test()->projectStatus->id]))
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

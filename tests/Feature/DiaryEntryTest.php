<?php

declare(strict_types=1);

use App\Models\DiaryEntry;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = asUser();
    $status = ProjectStatus::factory()->create();
    test()->project = Project::factory()
        ->forUser(test()->user)
        ->create(['status_id' => $status->id]);
});

it('can view diary entries', function () {
    DiaryEntry::factory()->forProject(test()->project)->count(3)->create();

    getJson(route('projects.diary.index', test()->project))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can create a diary entry', function () {
    $data = [
        'title' => 'Primera entrada',
        'content' => 'Hoy empecé el proyecto...',
        'type' => 'progress',
        'entry_date' => now()->toDateString(),
        'time_spent_minutes' => 120,
    ];

    postJson(route('projects.diary.store', test()->project), $data)
        ->assertCreated()
        ->assertJsonPath('title', 'Primera entrada');

    assertDatabaseHas('diary_entries', [
        'project_id' => test()->project->id,
        'title' => 'Primera entrada',
    ]);
});

it('can update a diary entry', function () {
    $entry = DiaryEntry::factory()->forProject(test()->project)->create();

    $data = [
        'title' => 'Título actualizado',
        'content' => 'Contenido actualizado',
        'type' => 'milestone',
    ];

    putJson(route('projects.diary.update', [test()->project, $entry]), $data)
        ->assertOk()
        ->assertJsonPath('title', 'Título actualizado');
});

it('can delete a diary entry', function () {
    $entry = DiaryEntry::factory()->forProject(test()->project)->create();

    deleteJson(route('projects.diary.destroy', [test()->project, $entry]))
        ->assertNoContent();

    assertDatabaseMissing('diary_entries', ['id' => $entry->id]);
});

it('requires content to create an entry', function () {
    $data = [
        'title' => 'Solo título',
        'type' => 'note',
    ];

    postJson(route('projects.diary.store', test()->project), $data)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['content']);
});

it('cannot access another user\'s project diary', function () {
    $otherUser = User::factory()->create();
    $otherProject = Project::factory()
        ->forUser($otherUser)
        ->create(['status_id' => test()->project->status_id]);

    getJson(route('projects.diary.index', $otherProject))
        ->assertForbidden();
});

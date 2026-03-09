<?php

declare(strict_types=1);

use App\Models\CalendarEvent;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    test()->user = asUser();
});

it('can view calendar events', function () {
    CalendarEvent::factory()
        ->forUser(test()->user)
        ->count(3)
        ->create(['event_date' => now()->startOfMonth()->addDays(10)]);

    getJson(route('calendar.index', [
        'year' => now()->year,
        'month' => now()->month,
    ]))
        ->assertOk()
        ->assertJsonStructure(['year', 'month', 'events']);
});

it('can create a calendar event', function () {
    $data = [
        'title' => 'Reunión importante',
        'description' => 'Descripción del evento',
        'type' => 'custom',
        'event_date' => now()->addDays(5)->toDateString(),
        'is_all_day' => true,
    ];

    postJson(route('calendar.events.store'), $data)
        ->assertCreated()
        ->assertJsonPath('title', 'Reunión importante');

    assertDatabaseHas('calendar_events', [
        'user_id' => test()->user->id,
        'title' => 'Reunión importante',
    ]);
});

it('can view upcoming events', function () {
    CalendarEvent::factory()
        ->forUser(test()->user)
        ->count(2)
        ->create(['event_date' => now()->addDays(5)]);

    CalendarEvent::factory()
        ->forUser(test()->user)
        ->create(['event_date' => now()->addDays(60)]);

    getJson(route('calendar.upcoming', ['days' => 30]))
        ->assertOk()
        ->assertJsonCount(2);
});

it('can delete a calendar event', function () {
    $event = CalendarEvent::factory()->forUser(test()->user)->create();

    deleteJson(route('calendar.events.destroy', $event))
        ->assertNoContent();

    assertDatabaseMissing('calendar_events', ['id' => $event->id]);
});

it('requires title and date to create an event', function () {
    $data = ['type' => 'custom'];

    postJson(route('calendar.events.store'), $data)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'event_date']);
});

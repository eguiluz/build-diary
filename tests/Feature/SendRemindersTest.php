<?php

declare(strict_types=1);

use App\Models\CalendarEvent;
use App\Models\Person;
use App\Models\User;
use App\Notifications\BirthdayReminderNotification;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\artisan;

beforeEach(function () {
    test()->user = User::factory()->create();
});

describe('Event Reminders', function () {
    it('sends event reminder when time is due', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-04 09:30:00'));

        $event = CalendarEvent::factory()->create([
            'user_id' => test()->user->id,
            'title' => 'Meeting',
            'event_date' => '2026-03-04',
            'event_time' => '10:00:00',
            'reminder_enabled' => true,
            'reminder_minutes_before' => 30,
            'reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')
            ->expectsOutput('Processing reminders...')
            ->assertSuccessful();

        Notification::assertSentTo(test()->user, EventReminderNotification::class);

        expect($event->fresh()->reminder_sent_at)->not->toBeNull();
    });

    it('does not send reminder if already sent', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-04 09:30:00'));

        CalendarEvent::factory()->create([
            'user_id' => test()->user->id,
            'event_date' => '2026-03-04',
            'event_time' => '10:00:00',
            'reminder_enabled' => true,
            'reminder_minutes_before' => 30,
            'reminder_sent_at' => now()->subHour(),
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, EventReminderNotification::class);
    });

    it('does not send reminder if time is not yet due', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-04 08:00:00'));

        CalendarEvent::factory()->create([
            'user_id' => test()->user->id,
            'event_date' => '2026-03-04',
            'event_time' => '10:00:00',
            'reminder_enabled' => true,
            'reminder_minutes_before' => 30,
            'reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, EventReminderNotification::class);
    });

    it('does not send reminder if event has passed', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-04 11:00:00'));

        CalendarEvent::factory()->create([
            'user_id' => test()->user->id,
            'event_date' => '2026-03-04',
            'event_time' => '10:00:00',
            'reminder_enabled' => true,
            'reminder_minutes_before' => 30,
            'reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, EventReminderNotification::class);
    });
});

describe('Birthday Reminders', function () {
    it('sends birthday reminder on correct day', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-08'));

        Person::factory()->create([
            'user_id' => test()->user->id,
            'name' => 'Juan',
            'birthday' => '1990-03-15',
            'birthday_reminder' => true,
            'reminder_days_before' => 7,
            'last_birthday_reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertSentTo(test()->user, BirthdayReminderNotification::class);
    });

    it('does not resend birthday reminder same year', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-08'));

        Person::factory()->create([
            'user_id' => test()->user->id,
            'birthday' => '1990-03-15',
            'birthday_reminder' => true,
            'reminder_days_before' => 7,
            'last_birthday_reminder_sent_at' => Carbon::parse('2026-03-08'),
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, BirthdayReminderNotification::class);
    });

    it('does not send reminder if day does not match', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-05'));

        Person::factory()->create([
            'user_id' => test()->user->id,
            'birthday' => '1990-03-15',
            'birthday_reminder' => true,
            'reminder_days_before' => 7,
            'last_birthday_reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, BirthdayReminderNotification::class);
    });

    it('does not send reminder if disabled', function () {
        Notification::fake();
        Carbon::setTestNow(Carbon::parse('2026-03-08'));

        Person::factory()->create([
            'user_id' => test()->user->id,
            'birthday' => '1990-03-15',
            'birthday_reminder' => false,
            'reminder_days_before' => 7,
            'last_birthday_reminder_sent_at' => null,
        ]);

        artisan('app:send-reminders')->assertSuccessful();

        Notification::assertNotSentTo(test()->user, BirthdayReminderNotification::class);
    });
});

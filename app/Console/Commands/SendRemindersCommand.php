<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\Person;
use App\Notifications\BirthdayReminderNotification;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class SendRemindersCommand extends Command
{
    protected $signature = 'app:send-reminders';

    protected $description = 'Send event and birthday reminders to users';

    public function handle(): int
    {
        $this->info('Processing reminders...');

        $eventCount = $this->processEventReminders();
        $birthdayCount = $this->processBirthdayReminders();

        $this->info("Sent {$eventCount} event reminder(s) and {$birthdayCount} birthday reminder(s).");

        return self::SUCCESS;
    }

    private function processEventReminders(): int
    {
        $now = Carbon::now();
        $count = 0;

        // Get events with reminders enabled that haven't been sent yet
        $events = CalendarEvent::query()
            ->where('reminder_enabled', true)
            ->whereNull('reminder_sent_at')
            ->whereNotNull('reminder_minutes_before')
            ->where('event_date', '>=', $now->toDateString())
            ->get();

        foreach ($events as $event) {
            $eventDateTime = $this->getEventDateTime($event);
            $reminderTime = $eventDateTime->copy()->subMinutes($event->reminder_minutes_before);

            // If we're past the reminder time but before the event, send the reminder
            if ($now->gte($reminderTime) && $now->lt($eventDateTime)) {
                $event->user->notify(new EventReminderNotification($event));
                $event->update(['reminder_sent_at' => $now]);
                $count++;

                Log::info("Sent event reminder for '{$event->title}' to user {$event->user_id}");
            }
        }

        return $count;
    }

    private function processBirthdayReminders(): int
    {
        $today = Carbon::today();
        $count = 0;

        // Get people with birthday reminders enabled
        $people = Person::query()
            ->where('birthday_reminder', true)
            ->whereNotNull('birthday')
            ->whereNotNull('reminder_days_before')
            ->get();

        foreach ($people as $person) {
            $birthday = $person->birthday->copy();
            // Set birthday to this year or next year
            $birthdayThisYear = $birthday->year($today->year);

            if ($birthdayThisYear->lt($today)) {
                $birthdayThisYear->addYear();
            }

            $daysUntilBirthday = $today->diffInDays($birthdayThisYear, false);
            $reminderDay = $birthdayThisYear->copy()->subDays($person->reminder_days_before);

            // Check if today is the reminder day and we haven't sent this year's reminder
            if ($today->isSameDay($reminderDay) && ! $this->birthdayReminderSentThisYear($person)) {
                $person->user->notify(new BirthdayReminderNotification($person, (int) $daysUntilBirthday));
                $person->update(['last_birthday_reminder_sent_at' => $today]);
                $count++;

                Log::info("Sent birthday reminder for '{$person->name}' to user {$person->user_id}");
            }
        }

        return $count;
    }

    private function getEventDateTime(CalendarEvent $event): Carbon
    {
        $date = $event->event_date->copy();

        if ($event->event_time) {
            $time = Carbon::parse($event->event_time);
            $date->setTime($time->hour, $time->minute);
        } else {
            // For all-day events, set reminder time to 8:00 AM
            $date->setTime(8, 0);
        }

        return $date;
    }

    private function birthdayReminderSentThisYear(Person $person): bool
    {
        if (! $person->last_birthday_reminder_sent_at) {
            return false;
        }

        return $person->last_birthday_reminder_sent_at->year === Carbon::today()->year;
    }
}

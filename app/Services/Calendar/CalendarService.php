<?php

declare(strict_types=1);

namespace App\Services\Calendar;

use App\Models\CalendarEvent;
use App\Models\Person;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class CalendarService
{
    /**
     * @return Collection<int, CalendarEvent|array<string, mixed>>
     */
    public function getEventsForMonth(User $user, int $year, int $month): Collection
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return $this->getEventsBetweenDates($user, $startDate, $endDate);
    }

    /**
     * @return Collection<int, CalendarEvent|array<string, mixed>>
     */
    public function getEventsBetweenDates(User $user, Carbon $startDate, Carbon $endDate): Collection
    {
        $events = CalendarEvent::forUser($user->id)
            ->betweenDates($startDate->toDateString(), $endDate->toDateString())
            ->with(['project', 'person'])
            ->get();

        $projectDeadlines = $this->getProjectDeadlines($user, $startDate, $endDate);
        $birthdays = $this->getBirthdays($user, $startDate, $endDate);

        /** @var Collection<int, CalendarEvent|array<string, mixed>> */
        return collect($events->toArray())
            ->merge($projectDeadlines)
            ->merge($birthdays)
            ->sortBy('event_date')
            ->values();
    }

    /**
     * @return Collection<int, CalendarEvent|array<string, mixed>>
     */
    public function getUpcomingEvents(User $user, int $days = 30): Collection
    {
        return $this->getEventsBetweenDates($user, now(), now()->addDays($days));
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function getProjectDeadlines(User $user, Carbon $startDate, Carbon $endDate): Collection
    {
        /** @var Collection<int, array<string, mixed>> */
        return Project::forUser($user->id)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->whereHas('status', fn ($q) => $q->where('is_completed', false))
            ->get()
            ->map(fn (Project $project) => $this->projectToEvent($project));
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function getBirthdays(User $user, Carbon $startDate, Carbon $endDate): Collection
    {
        $people = Person::forUser($user->id)
            ->whereNotNull('birthday')
            ->get();

        /** @var Collection<int, array<string, mixed>> */
        return $people
            ->map(fn (Person $person) => $this->personToBirthdayEvent($person, $startDate->year))
            ->filter(function ($event) use ($startDate, $endDate) {
                return $event &&
                    $event['event_date']->between($startDate, $endDate);
            });
    }

    /**
     * @return array<string, mixed>
     */
    private function projectToEvent(Project $project): array
    {
        return [
            'id' => 'project-'.$project->id,
            'title' => $project->title,
            'description' => $project->description,
            'type' => CalendarEvent::TYPE_DEADLINE,
            'event_date' => $project->due_date,
            'is_all_day' => true,
            'color' => '#EF4444',
            'project' => $project,
            'person' => null,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function personToBirthdayEvent(Person $person, int $year): ?array
    {
        if (! $person->birthday) {
            return null;
        }

        $birthdayThisYear = $person->birthday->copy()->year($year);

        return [
            'id' => 'birthday-'.$person->id,
            'title' => "Cumpleaños de {$person->name}",
            'description' => $person->age !== null ? 'Cumple '.($person->age + 1).' años' : null,
            'type' => CalendarEvent::TYPE_BIRTHDAY,
            'event_date' => $birthdayThisYear,
            'is_all_day' => true,
            'color' => '#8B5CF6',
            'project' => null,
            'person' => $person,
        ];
    }
}

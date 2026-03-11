<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;

final readonly class CalendarEventDTO
{
    public function __construct(
        public string $title,
        public string $type,
        public Carbon $eventDate,
        public ?string $description = null,
        public ?int $projectId = null,
        public ?int $personId = null,
        public ?string $eventTime = null,
        public ?Carbon $endDate = null,
        public bool $isAllDay = true,
        public bool $isRecurring = false,
        public ?string $recurrenceRule = null,
        public ?string $color = null,
        public bool $reminderEnabled = false,
        public ?int $reminderMinutesBefore = null,
    ) {}

    /**
     * @param array{
     *   title: string,
     *   type: string,
     *   event_date: string,
     *   description?: string|null,
     *   project_id?: int|null,
     *   person_id?: int|null,
     *   event_time?: string|null,
     *   end_date?: string|null,
     *   is_all_day?: bool,
     *   is_recurring?: bool,
     *   recurrence_rule?: string|null,
     *   color?: string|null,
     *   reminder_enabled?: bool,
     *   reminder_minutes_before?: int|null
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            type: $data['type'],
            eventDate: Carbon::parse($data['event_date']),
            description: $data['description'] ?? null,
            projectId: isset($data['project_id']) ? (int) $data['project_id'] : null,
            personId: isset($data['person_id']) ? (int) $data['person_id'] : null,
            eventTime: $data['event_time'] ?? null,
            endDate: isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            isAllDay: (bool) ($data['is_all_day'] ?? true),
            isRecurring: (bool) ($data['is_recurring'] ?? false),
            recurrenceRule: $data['recurrence_rule'] ?? null,
            color: $data['color'] ?? null,
            reminderEnabled: (bool) ($data['reminder_enabled'] ?? false),
            reminderMinutesBefore: isset($data['reminder_minutes_before']) ? (int) $data['reminder_minutes_before'] : null,
        );
    }
}

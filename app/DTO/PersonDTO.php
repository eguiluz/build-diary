<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;

final readonly class PersonDTO
{
    /**
     * @param  array<int>|null  $tagIds
     */
    public function __construct(
        public string $name,
        public ?string $email = null,
        public ?string $phone = null,
        public ?Carbon $birthday = null,
        public bool $birthdayReminder = false,
        public int $reminderDaysBefore = 7,
        public ?string $notes = null,
        public ?array $tagIds = null,
    ) {}

    /**
     * @param array{
     *   name: string,
     *   email?: string|null,
     *   phone?: string|null,
     *   birthday?: string|null,
     *   birthday_reminder?: bool,
     *   reminder_days_before?: int,
     *   notes?: string|null,
     *   tag_ids?: array<int>|null
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            birthday: isset($data['birthday']) ? Carbon::parse($data['birthday']) : null,
            birthdayReminder: (bool) ($data['birthday_reminder'] ?? false),
            reminderDaysBefore: (int) ($data['reminder_days_before'] ?? 7),
            notes: $data['notes'] ?? null,
            tagIds: $data['tag_ids'] ?? null,
        );
    }
}

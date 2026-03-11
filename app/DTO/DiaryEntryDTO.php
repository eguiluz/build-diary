<?php

declare(strict_types=1);

namespace App\DTO;

use Carbon\Carbon;

final readonly class DiaryEntryDTO
{
    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public string $content,
        public ?string $title = null,
        public string $type = 'note',
        public ?Carbon $entryDate = null,
        public ?string $entryTime = null,
        public ?int $timeSpentMinutes = null,
        public ?array $metadata = null,
    ) {}

    /**
     * @param array{
     *   content: string,
     *   title?: string|null,
     *   type?: string,
     *   entry_date?: string|null,
     *   entry_time?: string|null,
     *   time_spent_minutes?: int|null,
     *   metadata?: array<string, mixed>|null,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            content: $data['content'],
            title: $data['title'] ?? null,
            type: $data['type'] ?? 'note',
            entryDate: isset($data['entry_date']) ? Carbon::parse($data['entry_date']) : null,
            entryTime: $data['entry_time'] ?? null,
            timeSpentMinutes: isset($data['time_spent_minutes']) ? (int) $data['time_spent_minutes'] : null,
            metadata: $data['metadata'] ?? null,
        );
    }
}

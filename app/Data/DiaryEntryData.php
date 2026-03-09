<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;

final readonly class DiaryEntryData
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
     * @param  array<string, mixed>  $data
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

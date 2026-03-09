<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\Carbon;

final readonly class ProjectData
{
    /**
     * @param  array<string, mixed>|null  $metadata
     * @param  array<int>|null  $tagIds
     */
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?int $statusId = null,
        public ?int $personId = null,
        public ?int $categoryId = null,
        public ?Carbon $dueDate = null,
        public ?Carbon $startedAt = null,
        public ?int $priority = null,
        public ?array $metadata = null,
        public ?array $tagIds = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            statusId: isset($data['status_id']) ? (int) $data['status_id'] : null,
            personId: isset($data['person_id']) ? (int) $data['person_id'] : null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            startedAt: isset($data['started_at']) ? Carbon::parse($data['started_at']) : null,
            priority: isset($data['priority']) ? (int) $data['priority'] : null,
            metadata: $data['metadata'] ?? null,
            tagIds: $data['tag_ids'] ?? null,
        );
    }
}

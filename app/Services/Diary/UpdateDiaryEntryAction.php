<?php

declare(strict_types=1);

namespace App\Services\Diary;

use App\DTO\DiaryEntryDTO;
use App\Models\DiaryEntry;

final class UpdateDiaryEntryAction
{
    public function execute(DiaryEntry $entry, DiaryEntryDTO $dto): DiaryEntry
    {
        $entry->update([
            'title' => $dto->title,
            'content' => $dto->content,
            'type' => $dto->type,
            'entry_date' => $dto->entryDate ?? $entry->entry_date,
            'entry_time' => $dto->entryTime,
            'time_spent_minutes' => $dto->timeSpentMinutes,
            'metadata' => $dto->metadata,
        ]);

        return $entry->fresh();
    }
}

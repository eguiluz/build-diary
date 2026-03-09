<?php

declare(strict_types=1);

namespace App\Services\Diary;

use App\Data\DiaryEntryData;
use App\Models\DiaryEntry;

final class UpdateDiaryEntryAction
{
    public function execute(DiaryEntry $entry, DiaryEntryData $data): DiaryEntry
    {
        $entry->update([
            'title' => $data->title,
            'content' => $data->content,
            'type' => $data->type,
            'entry_date' => $data->entryDate ?? $entry->entry_date,
            'entry_time' => $data->entryTime,
            'time_spent_minutes' => $data->timeSpentMinutes,
            'metadata' => $data->metadata,
        ]);

        return $entry->fresh();
    }
}

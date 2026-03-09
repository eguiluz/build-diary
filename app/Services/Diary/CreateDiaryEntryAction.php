<?php

declare(strict_types=1);

namespace App\Services\Diary;

use App\Data\DiaryEntryData;
use App\Models\DiaryEntry;
use App\Models\Project;

final class CreateDiaryEntryAction
{
    public function execute(Project $project, DiaryEntryData $data): DiaryEntry
    {
        return DiaryEntry::create([
            'project_id' => $project->id,
            'title' => $data->title,
            'content' => $data->content,
            'type' => $data->type,
            'entry_date' => $data->entryDate ?? now()->toDateString(),
            'entry_time' => $data->entryTime,
            'time_spent_minutes' => $data->timeSpentMinutes,
            'metadata' => $data->metadata,
        ]);
    }
}

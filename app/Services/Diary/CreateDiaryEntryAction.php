<?php

declare(strict_types=1);

namespace App\Services\Diary;

use App\DTO\DiaryEntryDTO;
use App\Models\DiaryEntry;
use App\Models\Project;

final class CreateDiaryEntryAction
{
    public function execute(Project $project, DiaryEntryDTO $dto): DiaryEntry
    {
        return DiaryEntry::create([
            'project_id' => $project->id,
            'title' => $dto->title,
            'content' => $dto->content,
            'type' => $dto->type,
            'entry_date' => $dto->entryDate ?? now()->toDateString(),
            'entry_time' => $dto->entryTime,
            'time_spent_minutes' => $dto->timeSpentMinutes,
            'metadata' => $dto->metadata,
        ]);
    }
}

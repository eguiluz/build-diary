<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\Data\ProjectData;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Support\Str;

final class CreateProjectAction
{
    public function execute(User $user, ProjectData $data): Project
    {
        $project = new Project;
        $project->user_id = $user->id;
        $project->status_id = $data->statusId ?? $this->getDefaultStatusId();
        $project->person_id = $data->personId;
        $project->title = $data->title;
        $project->slug = $this->generateUniqueSlug($user, $data->title);
        $project->description = $data->description;
        $project->category_id = $data->categoryId;
        $project->due_date = $data->dueDate;
        $project->started_at = $data->startedAt;
        $project->priority = $data->priority ?? 0;
        $project->metadata = $data->metadata;
        $project->save();

        if (! empty($data->tagIds)) {
            $project->syncTags($data->tagIds);
        }

        return $project->load(['status', 'person', 'tags']);
    }

    private function getDefaultStatusId(): int
    {
        return ProjectStatus::default()->first()->id
            ?? ProjectStatus::first()->id
            ?? throw new \RuntimeException('No project statuses configured');
    }

    private function generateUniqueSlug(User $user, string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Project::forUser($user->id)->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

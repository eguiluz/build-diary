<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\Data\ProjectData;
use App\Models\Project;
use Illuminate\Support\Str;

final class UpdateProjectAction
{
    public function execute(Project $project, ProjectData $data): Project
    {
        if ($data->title !== $project->title) {
            $project->slug = $this->generateUniqueSlug($project, $data->title);
        }

        $project->status_id = $data->statusId ?? $project->status_id;
        $project->person_id = $data->personId;
        $project->title = $data->title;
        $project->description = $data->description;
        $project->category_id = $data->categoryId;
        $project->due_date = $data->dueDate;
        $project->started_at = $data->startedAt;
        $project->priority = $data->priority ?? $project->priority;
        $project->metadata = $data->metadata;

        if ($this->isBeingCompleted($project)) {
            $project->completed_at = now();
        } elseif ($this->isBeingReopened($project)) {
            $project->completed_at = null;
        }

        $project->save();

        if ($data->tagIds !== null) {
            $project->syncTags($data->tagIds);
        }

        return $project->load(['status', 'person', 'tags']);
    }

    private function isBeingCompleted(Project $project): bool
    {
        if (! $project->isDirty('status_id')) {
            return false;
        }

        return $project->status->is_completed === true && $project->completed_at === null;
    }

    private function isBeingReopened(Project $project): bool
    {
        if (! $project->isDirty('status_id')) {
            return false;
        }

        return $project->status->is_completed === false && $project->completed_at !== null;
    }

    private function generateUniqueSlug(Project $project, string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Project::forUser($project->user_id)
                ->where('slug', $slug)
                ->where('id', '!=', $project->id)
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}

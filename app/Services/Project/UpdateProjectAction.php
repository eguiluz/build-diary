<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\DTO\ProjectDTO;
use App\Models\Project;
use Illuminate\Support\Str;

final class UpdateProjectAction
{
    public function execute(Project $project, ProjectDTO $dto): Project
    {
        if ($dto->title !== $project->title) {
            $project->slug = $this->generateUniqueSlug($project, $dto->title);
        }

        $project->status_id = $dto->statusId ?? $project->status_id;
        $project->person_id = $dto->personId;
        $project->title = $dto->title;
        $project->description = $dto->description;
        $project->category_id = $dto->categoryId;
        $project->due_date = $dto->dueDate;
        $project->started_at = $dto->startedAt;
        $project->priority = $dto->priority ?? $project->priority;
        $project->metadata = $dto->metadata;

        if ($this->isBeingCompleted($project)) {
            $project->completed_at = now();
        } elseif ($this->isBeingReopened($project)) {
            $project->completed_at = null;
        }

        $project->save();

        if ($dto->tagIds !== null) {
            $project->syncTags($dto->tagIds);
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

<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\DTO\ProjectDTO;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Support\Str;

final class CreateProjectAction
{
    public function execute(User $user, ProjectDTO $dto): Project
    {
        $project = new Project;
        $project->user_id = $user->id;
        $project->status_id = $dto->statusId ?? $this->getDefaultStatusId();
        $project->person_id = $dto->personId;
        $project->title = $dto->title;
        $project->slug = $this->generateUniqueSlug($user, $dto->title);
        $project->description = $dto->description;
        $project->category_id = $dto->categoryId;
        $project->due_date = $dto->dueDate;
        $project->started_at = $dto->startedAt;
        $project->priority = $dto->priority ?? 0;
        $project->metadata = $dto->metadata;
        $project->save();

        if (! empty($dto->tagIds)) {
            $project->syncTags($dto->tagIds);
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

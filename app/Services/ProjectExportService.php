<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProjectExportService
{
    /**
     * Export a project to a ZIP file containing JSON data and files.
     */
    public function export(Project $project): string
    {
        $project->load([
            'status',
            'category',
            'person',
            'files',
            'diaryEntries',
            'links',
            'tasks',
            'expenses',
            'calendarEvents',
            'tags',
        ]);

        $exportData = $this->buildExportData($project);
        $zipPath = $this->createZipArchive($project, $exportData);

        return $zipPath;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildExportData(Project $project): array
    {
        return [
            'version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'project' => [
                'title' => $project->title,
                'description' => $project->description,
                'due_date' => $project->due_date?->toDateString(),
                'started_at' => $project->started_at?->toDateString(),
                'completed_at' => $project->completed_at?->toDateString(),
                'priority' => $project->priority,
                'is_archived' => $project->is_archived,
                'is_public' => $project->is_public,
                'person_reason' => $project->person_reason,
                'metadata' => $project->metadata,
            ],
            'category' => $project->category ? [
                'name' => $project->category->name,
                'slug' => $project->category->slug,
                'color' => $project->category->color,
            ] : null,
            'status' => [
                'name' => $project->status->name,
                'color' => $project->status->color,
            ],
            'person' => $project->person ? [
                'name' => $project->person->name,
                'email' => $project->person->email,
                'phone' => $project->person->phone,
                'birthday' => $project->person->birthday?->toDateString(),
                'notes' => $project->person->notes,
                'reminder_enabled' => $project->person->birthday_reminder,
                'reminder_days_before' => $project->person->reminder_days_before,
            ] : null,
            'tags' => $project->tags->map(fn ($tag): array => [
                'name' => $tag->name,
                'color' => $tag->color,
            ])->toArray(),
            'files' => $project->files->map(fn ($file): array => [
                'name' => $file->name,
                'original_name' => $file->original_name,
                'path' => $file->path,
                'disk' => $file->disk,
                'mime_type' => $file->mime_type,
                'size' => $file->size,
                'type' => $file->type,
                'description' => $file->description,
                'order' => $file->order,
                '_file_path' => $file->path,
            ])->toArray(),
            'diary_entries' => $project->diaryEntries->map(fn ($entry): array => [
                'title' => $entry->title,
                'content' => $entry->content,
                'type' => $entry->type,
                'entry_date' => $entry->entry_date->toDateString(),
                'entry_time' => $entry->entry_time?->format('H:i:s'),
                'time_spent_minutes' => $entry->time_spent_minutes,
                'metadata' => $entry->metadata,
            ])->toArray(),
            'links' => $project->links->map(fn ($link): array => [
                'title' => $link->title,
                'url' => $link->url,
                'description' => $link->description,
                'order' => $link->order,
            ])->toArray(),
            'tasks' => $project->tasks->map(fn ($task): array => [
                'title' => $task->title,
                'description' => $task->description,
                'is_completed' => $task->is_completed,
                'completed_at' => $task->completed_at?->toDateTimeString(),
                'order' => $task->order,
            ])->toArray(),
            'expenses' => $project->expenses->map(fn ($expense): array => [
                'name' => $expense->name,
                'description' => $expense->description,
                'category' => $expense->category,
                'quantity' => (float) $expense->quantity,
                'unit' => $expense->unit,
                'unit_price' => (float) $expense->unit_price,
                'supplier' => $expense->supplier,
                'url' => $expense->url,
                'purchased_at' => $expense->purchased_at?->toDateString(),
                'is_purchased' => $expense->is_purchased,
            ])->toArray(),
            'calendar_events' => $project->calendarEvents->map(fn ($event): array => [
                'title' => $event->title,
                'description' => $event->description,
                'type' => $event->type,
                'event_date' => $event->event_date->toDateString(),
                'event_time' => $event->event_time,
                'end_date' => $event->end_date?->toDateString(),
                'is_all_day' => $event->is_all_day,
                'is_recurring' => $event->is_recurring,
                'recurrence_rule' => $event->recurrence_rule,
                'color' => $event->color,
                'reminder_enabled' => $event->reminder_enabled,
                'reminder_minutes_before' => $event->reminder_minutes_before,
            ])->toArray(),
        ];
    }

    /**
     * @param  array<string, mixed>  $exportData
     */
    private function createZipArchive(Project $project, array $exportData): string
    {
        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $slug = $project->slug;
        $zipFilename = "project-{$slug}-".now()->format('Y-m-d-His').'.zip';
        $zipPath = "{$tempDir}/{$zipFilename}";

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create ZIP file');
        }

        // Add project data JSON
        $zip->addFromString('project.json', json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Add files
        $zip->addEmptyDir('files');
        foreach ($project->files as $file) {
            $filePath = Storage::disk($file->disk)->path($file->path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, 'files/'.basename($file->path));
            }
        }

        $zip->close();

        return $zipPath;
    }
}

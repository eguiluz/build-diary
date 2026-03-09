<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\DiaryEntry;
use App\Models\Person;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectExpense;
use App\Models\ProjectFile;
use App\Models\ProjectLink;
use App\Models\ProjectStatus;
use App\Models\ProjectTask;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProjectImportService
{
    /**
     * Import a project from a ZIP file.
     */
    public function import(string $zipPath, User $user): Project
    {
        $extractPath = $this->extractZip($zipPath);

        try {
            $jsonPath = "{$extractPath}/project.json";
            if (! file_exists($jsonPath)) {
                throw new \RuntimeException('El archivo ZIP no contiene project.json');
            }

            $data = json_decode(file_get_contents($jsonPath), true);
            if (! $data) {
                throw new \RuntimeException('El archivo project.json no es válido');
            }

            return DB::transaction(fn () => $this->createProject($data, $user, $extractPath));
        } finally {
            // Cleanup extracted files
            $this->deleteDirectory($extractPath);
        }
    }

    private function extractZip(string $zipPath): string
    {
        $extractPath = storage_path('app/temp/import-'.Str::random(16));
        mkdir($extractPath, 0755, true);

        $zip = new ZipArchive;
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('No se puede abrir el archivo ZIP');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        return $extractPath;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createProject(array $data, User $user, string $extractPath): Project
    {
        $projectData = $data['project'] ?? [];

        // Find or create status
        $statusId = $this->resolveStatus($data['status'] ?? null, $user);

        // Find or create person
        $personId = $this->resolvePerson($data['person'] ?? null, $user);

        // Find or create category
        $categoryId = $this->resolveCategory($data['category'] ?? null);

        // Create project with unique slug
        $baseSlug = Str::slug($projectData['title'] ?? 'imported-project');
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $project = Project::create([
            'user_id' => $user->id,
            'status_id' => $statusId,
            'person_id' => $personId,
            'category_id' => $categoryId,
            'title' => ($projectData['title'] ?? 'Proyecto importado').' (importado)',
            'slug' => $slug,
            'description' => $projectData['description'] ?? null,
            'due_date' => $projectData['due_date'] ?? null,
            'started_at' => $projectData['started_at'] ?? null,
            'completed_at' => $projectData['completed_at'] ?? null,
            'priority' => $projectData['priority'] ?? 0,
            'is_archived' => $projectData['is_archived'] ?? false,
            'is_public' => $projectData['is_public'] ?? false,
            'person_reason' => $projectData['person_reason'] ?? null,
            'metadata' => $projectData['metadata'] ?? null,
        ]);

        // Import related data
        $this->importTags($project, $data['tags'] ?? [], $user);
        $this->importFiles($project, $data['files'] ?? [], $extractPath);
        $this->importDiaryEntries($project, $data['diary_entries'] ?? []);
        $this->importLinks($project, $data['links'] ?? []);
        $this->importTasks($project, $data['tasks'] ?? []);
        $this->importExpenses($project, $data['expenses'] ?? []);
        $this->importCalendarEvents($project, $data['calendar_events'] ?? []);

        return $project;
    }

    /**
     * @param  array<string, mixed>|null  $statusData
     */
    private function resolveStatus(?array $statusData, User $user): int
    {
        if (! $statusData) {
            // Get default status
            $defaultStatus = ProjectStatus::where('is_default', true)->first();
            if ($defaultStatus) {
                return $defaultStatus->id;
            }

            $firstStatus = ProjectStatus::first();

            return $firstStatus ? $firstStatus->id : 1;
        }

        // Find existing status by name or create for user
        $status = ProjectStatus::where('name', $statusData['name'])->first();

        if (! $status) {
            $status = ProjectStatus::create([
                'name' => $statusData['name'],
                'color' => $statusData['color'] ?? 'gray',
                'order' => ProjectStatus::max('order') + 1,
            ]);
        }

        return $status->id;
    }

    /**
     * @param  array<string, mixed>|null  $personData
     */
    private function resolvePerson(?array $personData, User $user): ?int
    {
        if (! $personData || empty($personData['name'])) {
            return null;
        }

        // Find by name or create
        $person = Person::where('user_id', $user->id)
            ->where('name', $personData['name'])
            ->first();

        if (! $person) {
            $person = Person::create([
                'user_id' => $user->id,
                'name' => $personData['name'],
                'email' => $personData['email'] ?? null,
                'phone' => $personData['phone'] ?? null,
                'birthday' => $personData['birthday'] ?? null,
                'notes' => $personData['notes'] ?? null,
                'birthday_reminder' => $personData['reminder_enabled'] ?? false,
                'reminder_days_before' => $personData['reminder_days_before'] ?? 7,
            ]);
        } else {
            // Update birthday_reminder if person exists
            if (isset($personData['reminder_enabled'])) {
                $person->update([
                    'birthday_reminder' => $personData['reminder_enabled'],
                    'reminder_days_before' => $personData['reminder_days_before'] ?? $person->reminder_days_before,
                ]);
            }
        }

        return $person->id;
    }

    /**
     * @param  array<string, mixed>|null  $categoryData
     */
    private function resolveCategory(?array $categoryData): ?int
    {
        if (! $categoryData || empty($categoryData['slug'])) {
            return null;
        }

        // Find by slug or create
        $category = ProjectCategory::where('slug', $categoryData['slug'])->first();

        if (! $category) {
            $category = ProjectCategory::create([
                'name' => $categoryData['name'] ?? $categoryData['slug'],
                'slug' => $categoryData['slug'],
                'color' => $categoryData['color'] ?? 'gray',
            ]);
        }

        return $category->id;
    }

    /**
     * @param  array<int, array<string, mixed>>  $tags
     */
    private function importTags(Project $project, array $tags, User $user): void
    {
        $tagIds = [];

        foreach ($tags as $tagData) {
            if (empty($tagData['name'])) {
                continue;
            }

            $tag = Tag::where('user_id', $user->id)
                ->where('name', $tagData['name'])
                ->first();

            if (! $tag) {
                $tag = Tag::create([
                    'user_id' => $user->id,
                    'name' => $tagData['name'],
                    'color' => $tagData['color'] ?? null,
                ]);
            }

            $tagIds[] = $tag->id;
        }

        if ($tagIds) {
            $project->tags()->sync($tagIds);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $files
     */
    private function importFiles(Project $project, array $files, string $extractPath): void
    {
        foreach ($files as $fileData) {
            $sourceFile = "{$extractPath}/files/".basename($fileData['_file_path'] ?? $fileData['path'] ?? $fileData['name'] ?? '');

            if (! file_exists($sourceFile)) {
                continue;
            }

            // Generate new filename
            $extension = pathinfo($fileData['original_name'] ?? $fileData['name'] ?? 'file', PATHINFO_EXTENSION);
            $newPath = 'projects/'.Str::random(40).'.'.$extension;

            // Copy file to storage
            $disk = $fileData['disk'] ?? 'public';
            Storage::disk($disk)->put($newPath, file_get_contents($sourceFile));

            ProjectFile::create([
                'project_id' => $project->id,
                'name' => $fileData['name'] ?? basename($sourceFile),
                'original_name' => $fileData['original_name'] ?? basename($sourceFile),
                'path' => $newPath,
                'disk' => $disk,
                'mime_type' => $fileData['mime_type'] ?? 'application/octet-stream',
                'size' => $fileData['size'] ?? filesize($sourceFile),
                'type' => $fileData['type'] ?? 'attachment',
                'description' => $fileData['description'] ?? null,
                'order' => $fileData['order'] ?? 0,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $entries
     */
    private function importDiaryEntries(Project $project, array $entries): void
    {
        foreach ($entries as $entryData) {
            DiaryEntry::create([
                'project_id' => $project->id,
                'title' => $entryData['title'] ?? null,
                'content' => $entryData['content'] ?? '',
                'type' => $entryData['type'] ?? 'note',
                'entry_date' => $entryData['entry_date'] ?? now()->toDateString(),
                'entry_time' => $entryData['entry_time'] ?? null,
                'time_spent_minutes' => $entryData['time_spent_minutes'] ?? null,
                'metadata' => $entryData['metadata'] ?? null,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $links
     */
    private function importLinks(Project $project, array $links): void
    {
        foreach ($links as $linkData) {
            ProjectLink::create([
                'project_id' => $project->id,
                'title' => $linkData['title'] ?? 'Sin título',
                'url' => $linkData['url'] ?? '',
                'description' => $linkData['description'] ?? null,
                'order' => $linkData['order'] ?? 0,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $tasks
     */
    private function importTasks(Project $project, array $tasks): void
    {
        foreach ($tasks as $taskData) {
            ProjectTask::create([
                'project_id' => $project->id,
                'title' => $taskData['title'] ?? 'Sin título',
                'description' => $taskData['description'] ?? null,
                'is_completed' => $taskData['is_completed'] ?? false,
                'completed_at' => $taskData['completed_at'] ?? null,
                'order' => $taskData['order'] ?? 0,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $expenses
     */
    private function importExpenses(Project $project, array $expenses): void
    {
        foreach ($expenses as $expenseData) {
            ProjectExpense::create([
                'project_id' => $project->id,
                'name' => $expenseData['name'] ?? 'Gasto importado',
                'description' => $expenseData['description'] ?? null,
                'category' => $expenseData['category'] ?? null,
                'quantity' => $expenseData['quantity'] ?? 1,
                'unit' => $expenseData['unit'] ?? null,
                'unit_price' => $expenseData['unit_price'] ?? 0,
                'supplier' => $expenseData['supplier'] ?? null,
                'url' => $expenseData['url'] ?? null,
                'purchased_at' => $expenseData['purchased_at'] ?? null,
                'is_purchased' => $expenseData['is_purchased'] ?? false,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $events
     */
    private function importCalendarEvents(Project $project, array $events): void
    {
        foreach ($events as $eventData) {
            CalendarEvent::create([
                'user_id' => $project->user_id,
                'project_id' => $project->id,
                'title' => $eventData['title'] ?? 'Evento importado',
                'description' => $eventData['description'] ?? null,
                'type' => $eventData['type'] ?? 'custom',
                'event_date' => $eventData['event_date'] ?? now()->toDateString(),
                'event_time' => $eventData['event_time'] ?? null,
                'end_date' => $eventData['end_date'] ?? null,
                'is_all_day' => $eventData['is_all_day'] ?? false,
                'is_recurring' => $eventData['is_recurring'] ?? false,
                'recurrence_rule' => $eventData['recurrence_rule'] ?? null,
                'color' => $eventData['color'] ?? null,
                'reminder_enabled' => $eventData['reminder_enabled'] ?? false,
                'reminder_minutes_before' => $eventData['reminder_minutes_before'] ?? null,
            ]);
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir) ?: [], ['.', '..']);
        foreach ($files as $file) {
            $path = "{$dir}/{$file}";
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}

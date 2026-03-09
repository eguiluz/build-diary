<?php

declare(strict_types=1);

namespace App\Services\File;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UploadProjectFileAction
{
    public function execute(Project $project, UploadedFile $file, ?string $description = null): ProjectFile
    {
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType() ?? 'application/octet-stream';
        $type = ProjectFile::detectType($mimeType, $extension);

        $filename = $this->generateFilename($file);
        $path = $this->getStoragePath($project, $type, $filename);

        $disk = config('filesystems.default', 'local');
        Storage::disk($disk)->putFileAs(dirname($path), $file, basename($path));

        return ProjectFile::create([
            'project_id' => $project->id,
            'name' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => $disk,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
            'type' => $type,
            'description' => $description,
            'order' => $this->getNextOrder($project),
        ]);
    }

    /**
     * @param  array<int, UploadedFile>  $files
     * @return array<int, ProjectFile>
     */
    public function executeMultiple(Project $project, array $files): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFiles[] = $this->execute($project, $file);
        }

        return $uploadedFiles;
    }

    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return $name.'-'.Str::random(8).'.'.$extension;
    }

    private function getStoragePath(Project $project, string $type, string $filename): string
    {
        return sprintf('projects/%d/%s/%s', $project->id, $type, $filename);
    }

    private function getNextOrder(Project $project): int
    {
        return ($project->files()->max('order') ?? 0) + 1;
    }
}

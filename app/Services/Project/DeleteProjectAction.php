<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

final class DeleteProjectAction
{
    public function execute(Project $project, bool $forceDelete = false): void
    {
        if ($forceDelete) {
            $this->deleteProjectFiles($project);
            $project->forceDelete();
        } else {
            $project->delete();
        }
    }

    private function deleteProjectFiles(Project $project): void
    {
        foreach ($project->files as $file) {
            Storage::disk($file->disk)->delete($file->path);
        }
    }
}

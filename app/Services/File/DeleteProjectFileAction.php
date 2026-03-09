<?php

declare(strict_types=1);

namespace App\Services\File;

use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;

final class DeleteProjectFileAction
{
    public function execute(ProjectFile $file): void
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();
    }

    /**
     * @param  array<int>  $fileIds
     */
    public function executeMultiple(array $fileIds): int
    {
        $files = ProjectFile::whereIn('id', $fileIds)->get();
        $count = 0;

        foreach ($files as $file) {
            $this->execute($file);
            $count++;
        }

        return $count;
    }
}

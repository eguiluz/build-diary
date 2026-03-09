<?php

declare(strict_types=1);

namespace App\Services\Project;

use App\Models\Project;

final class ArchiveProjectAction
{
    public function archive(Project $project): Project
    {
        $project->is_archived = true;
        $project->save();

        return $project;
    }

    public function unarchive(Project $project): Project
    {
        $project->is_archived = false;
        $project->save();

        return $project;
    }
}

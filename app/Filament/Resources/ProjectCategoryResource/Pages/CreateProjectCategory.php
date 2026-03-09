<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectCategoryResource\Pages;

use App\Filament\Resources\ProjectCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProjectCategory extends CreateRecord
{
    protected static string $resource = ProjectCategoryResource::class;
}

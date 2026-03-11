<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('import-project')
                ->label('Importar proyecto')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->url(fn () => route('filament.admin.pages.import-project')),
        ];
    }
}

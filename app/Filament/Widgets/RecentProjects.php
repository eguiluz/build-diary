<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProjects extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->with(['status', 'person', 'category'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->url(fn (Project $record): string => route('filament.admin.resources.projects.edit', ['record' => $record])),
                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state, $record) => $record->status->color ?? 'gray'),
                TextColumn::make('category.name')
                    ->label('Categoría'),
                TextColumn::make('person.name')
                    ->label('Persona'),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->since(),
            ])
            ->paginated(false);
    }
}

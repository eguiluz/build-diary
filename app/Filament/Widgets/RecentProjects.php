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
                    ->label(__('app.project.title'))
                    ->searchable()
                    ->url(fn (Project $record): string => route('filament.admin.resources.projects.edit', ['record' => $record])),
                TextColumn::make('status.name')
                    ->label(__('app.project.status'))
                    ->badge()
                    ->color(fn ($state, $record) => $record->status->color ?? 'gray'),
                TextColumn::make('category.name')
                    ->label(__('app.project.category')),
                TextColumn::make('person.name')
                    ->label(__('app.person.label')),
                TextColumn::make('updated_at')
                    ->label(__('app.common.updated_at'))
                    ->since(),
            ])
            ->paginated(false);
    }
}

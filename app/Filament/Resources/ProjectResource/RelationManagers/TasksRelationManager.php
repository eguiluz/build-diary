<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tareas';

    protected static ?string $icon = 'heroicon-o-clipboard-document-check';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Tarea')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_completed')
                    ->label('Completada')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var User $user */
        $user = Auth::user();
        $showCompleted = $user->getPreference('show_completed_tasks', true);

        return $table
            ->recordTitleAttribute('title')
            ->reorderable('order')
            ->defaultSort('order')
            ->modifyQueryUsing(fn (Builder $query) => $showCompleted ? $query : $query->where('is_completed', false))
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_completed')
                    ->label('')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update([
                            'completed_at' => $state ? now() : null,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tarea')
                    ->searchable()
                    ->description(fn ($record) => $record->description)
                    ->wrap()
                    ->extraAttributes(fn ($record) => $record->is_completed ? ['class' => 'line-through opacity-60'] : []),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Pendiente')
                    ->color(fn ($state) => $state ? 'success' : null),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('Estado')
                    ->placeholder('Todas')
                    ->trueLabel('Completadas')
                    ->falseLabel('Pendientes'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva tarea')
                    ->modalHeading('Añadir tarea'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->icon(fn ($record) => $record->is_completed ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_completed ? 'warning' : 'success')
                    ->tooltip(fn ($record) => $record->is_completed ? 'Marcar como pendiente' : 'Marcar como completada')
                    ->action(function ($record) {
                        $record->is_completed
                            ? $record->markAsIncomplete()
                            : $record->markAsCompleted();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('complete')
                        ->label('Marcar completadas')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->markAsCompleted())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('incomplete')
                        ->label('Marcar pendientes')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->markAsIncomplete())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin tareas')
            ->emptyStateDescription('Añade tareas para hacer seguimiento del progreso del proyecto.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}

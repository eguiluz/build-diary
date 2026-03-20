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

    protected static ?string $icon = 'heroicon-o-clipboard-document-check';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('app.task.plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('app.task.label'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label(__('app.task.description'))
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_completed')
                    ->label(__('app.task.is_completed'))
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
                    ->label(__('app.task.label'))
                    ->searchable()
                    ->description(fn ($record) => $record->description)
                    ->wrap()
                    ->extraAttributes(fn ($record) => $record->is_completed ? ['class' => 'line-through opacity-60'] : []),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('app.task.is_completed'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder(__('app.task.pending'))
                    ->color(fn ($state) => $state ? 'success' : null),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label(__('app.task.filter_status'))
                    ->placeholder(__('app.task.filter_all'))
                    ->trueLabel(__('app.task.filter_completed'))
                    ->falseLabel(__('app.task.filter_pending')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('app.task.add'))
                    ->modalHeading(__('app.task.add_heading')),
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
                        ->label(__('app.task.bulk_complete'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->markAsCompleted())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('incomplete')
                        ->label(__('app.task.bulk_incomplete'))
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->markAsIncomplete())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('app.task.empty_heading'))
            ->emptyStateDescription(__('app.task.empty_desc'))
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}

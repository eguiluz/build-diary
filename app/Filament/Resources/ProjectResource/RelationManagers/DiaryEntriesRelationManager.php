<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\DiaryEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DiaryEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'diaryEntries';

    protected static ?string $title = 'Diario';

    protected static ?string $icon = 'heroicon-o-book-open';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options(DiaryEntry::getTypes())
                    ->default(DiaryEntry::TYPE_NOTE)
                    ->required(),
                Forms\Components\RichEditor::make('content')
                    ->label('Contenido')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('entry_date')
                    ->label('Fecha')
                    ->required()
                    ->default(now()),
                Forms\Components\TimePicker::make('entry_time')
                    ->label('Hora')
                    ->seconds(false)
                    ->default(now()),
                Forms\Components\TextInput::make('time_spent_minutes')
                    ->label('Tiempo dedicado (minutos)')
                    ->numeric()
                    ->minValue(0)
                    ->step(5),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->defaultSort('entry_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => DiaryEntry::getTypes()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'note' => 'gray',
                        'progress' => 'info',
                        'milestone' => 'success',
                        'issue' => 'danger',
                        'solution' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->description(fn ($record) => $record->entry_time?->format('H:i'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Contenido')
                    ->html()
                    ->limit(100),
                Tables\Columns\TextColumn::make('time_spent_minutes')
                    ->label('Tiempo')
                    ->formatStateUsing(fn ($state) => $state ? floor($state / 60).'h '.($state % 60).'m' : '-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

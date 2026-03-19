<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\DiaryEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                Forms\Components\FileUpload::make('images')
                    ->label('Imágenes')
                    ->multiple()
                    ->image()
                    ->imagePreviewHeight('120')
                    ->directory('diary-entry-images')
                    ->disk('public')
                    ->reorderable()
                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, ?DiaryEntry $record): void {
                        if ($record instanceof DiaryEntry) {
                            $component->state($record->images->pluck('path')->toArray());
                        }
                    })
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('images_count')
                    ->label('Imágenes')
                    ->counts('images')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, Table $table): Model {
                        /** @var array<int, string> $images */
                        $images = $data['images'] ?? [];

                        $relationship = $table->getRelationship();
                        $record = new DiaryEntry;
                        $record->fill($data);
                        /** @phpstan-ignore-next-line */
                        $relationship->save($record);

                        foreach ($images as $index => $path) {
                            $record->images()->create([
                                'path' => $path,
                                'disk' => 'public',
                                'original_name' => basename((string) $path),
                                'order' => $index,
                            ]);
                        }

                        return $record;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(function (array $data, DiaryEntry $record): DiaryEntry {
                        /** @var array<int, string> $images */
                        $images = $data['images'] ?? [];

                        $record->update($data);

                        $newPaths = collect($images);
                        $record->images()->whereNotIn('path', $newPaths->toArray())->delete();

                        $existingPaths = $record->images()->pluck('path');
                        foreach ($newPaths->diff($existingPaths) as $index => $path) {
                            $record->images()->create([
                                'path' => $path,
                                'disk' => 'public',
                                'original_name' => basename((string) $path),
                                'order' => $index,
                            ]);
                        }

                        return $record;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

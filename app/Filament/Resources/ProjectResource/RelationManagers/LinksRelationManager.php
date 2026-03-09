<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LinksRelationManager extends RelationManager
{
    protected static string $relationship = 'links';

    protected static ?string $title = 'Enlaces';

    protected static ?string $icon = 'heroicon-o-link';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->url()
                    ->maxLength(2048),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->limit(50),
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

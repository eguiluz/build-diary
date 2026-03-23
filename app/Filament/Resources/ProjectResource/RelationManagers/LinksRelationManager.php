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

    protected static ?string $icon = 'heroicon-o-link';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('app.project_link.section_title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('app.project_link.title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label(__('app.project_link.url'))
                    ->required()
                    ->url()
                    ->maxLength(2048),
                Forms\Components\Textarea::make('description')
                    ->label(__('app.project_link.description'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('app.project_link.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label(__('app.project_link.url'))
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('app.project_link.create')),
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

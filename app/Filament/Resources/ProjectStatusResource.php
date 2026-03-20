<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectStatusResource\Pages;
use App\Models\ProjectStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectStatusResource extends Resource
{
    protected static ?string $model = ProjectStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?int $navigationSort = 31;

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.settings');
    }

    public static function getModelLabel(): string
    {
        return __('app.project_status.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.project_status.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('app.project_status.name'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->label(__('app.project_status.slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('app.project_status.color'))
                    ->required()
                    ->default('#6B7280'),
                Forms\Components\TextInput::make('order')
                    ->label(__('app.project_status.order'))
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_default')
                    ->label(__('app.project_status.is_default'))
                    ->helperText(__('app.project_status.is_default_helper')),
                Forms\Components\Toggle::make('is_completed')
                    ->label(__('app.project_status.is_completed_label'))
                    ->helperText(__('app.project_status.is_completed_helper')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('app.project_status.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('app.project_status.color')),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('app.project_status.order'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('app.project_status.is_default'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_completed')
                    ->label(__('app.project_status.is_completed'))
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectStatuses::route('/'),
            'create' => Pages\CreateProjectStatus::route('/create'),
            'edit' => Pages\EditProjectStatus::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FilesRelationManager extends RelationManager
{
    protected static string $relationship = 'files';

    protected static ?string $title = 'Archivos';

    protected static ?string $icon = 'heroicon-o-paper-clip';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('Archivo')
                    ->required()
                    ->preserveFilenames()
                    ->directory('project-files')
                    ->disk('public')
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state instanceof TemporaryUploadedFile) {
                            $set('name', $state->getClientOriginalName());
                            $set('original_name', $state->getClientOriginalName());
                            $set('mime_type', $state->getMimeType());
                            $set('size', $state->getSize());
                            $set('type', $this->determineFileType($state->getMimeType(), $state->getClientOriginalExtension()));
                        }
                    })
                    ->live(),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('original_name'),
                Forms\Components\Hidden::make('mime_type'),
                Forms\Components\Hidden::make('size'),
                Forms\Components\Hidden::make('type'),
                Forms\Components\Hidden::make('disk')->default('public'),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
            ]);
    }

    private function determineFileType(?string $mimeType, ?string $extension): string
    {
        if ($mimeType && str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if ($extension === 'stl' || $mimeType === 'model/stl') {
            return 'stl';
        }

        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }

        return 'attachment';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mime_type')
                    ->label('Tipo'),
                Tables\Columns\TextColumn::make('size')
                    ->label('Tamaño')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 2).' KB' : '-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Subido')
                    ->dateTime()
                    ->sortable(),
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

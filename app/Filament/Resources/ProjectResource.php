<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectExportService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?string $modelLabel = 'Proyecto';

    protected static ?string $pluralModelLabel = 'Proyectos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información básica')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Estado y asignación')
                    ->schema([
                        Forms\Components\Select::make('status_id')
                            ->label('Estado')
                            ->relationship('status', 'name')
                            ->required()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label('Persona asociada')
                            ->relationship('person', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('person_reason')
                            ->label('Motivo de la asociación')
                            ->options(Project::personReasons())
                            ->visible(fn ($get) => $get('person_id') !== null)
                            ->native(false),
                        Forms\Components\ToggleButtons::make('priority')
                            ->label('Prioridad')
                            ->options(Project::priorities())
                            ->icons([
                                Project::PRIORITY_LOW => 'heroicon-o-arrow-down',
                                Project::PRIORITY_MEDIUM => 'heroicon-o-minus',
                                Project::PRIORITY_HIGH => 'heroicon-o-arrow-up',
                            ])
                            ->colors([
                                Project::PRIORITY_LOW => 'success',
                                Project::PRIORITY_MEDIUM => 'warning',
                                Project::PRIORITY_HIGH => 'danger',
                            ])
                            ->inline(),
                        Forms\Components\Toggle::make('is_archived')
                            ->label('Archivado'),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Público')
                            ->helperText('Si está activo, el proyecto será visible en la web pública')
                            ->live(),
                        Forms\Components\Placeholder::make('public_url')
                            ->label('URL pública')
                            ->content(fn ($record) => $record?->slug ? url('/p/'.$record->slug) : '-')
                            ->visible(fn ($get) => $get('is_public'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Fechas')
                    ->schema([
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Fecha límite'),
                        Forms\Components\DatePicker::make('started_at')
                            ->label('Fecha de inicio'),
                        Forms\Components\DatePicker::make('completed_at')
                            ->label('Fecha de finalización'),
                    ])->columns(3),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($record) => $record->status->color ?? 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn ($record) => $record->category->color ?? 'gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Persona')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Fecha límite')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Project::priorities()[$state] ?? '-')
                    ->color(fn ($state) => match ($state) {
                        Project::PRIORITY_LOW => 'success',
                        Project::PRIORITY_MEDIUM => 'warning',
                        Project::PRIORITY_HIGH => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_archived')
                    ->label('Archivado')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Público')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Estado')
                    ->relationship('status', 'name'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_archived')
                    ->label('Archivado'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                    ->label('Exportar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Project $record): BinaryFileResponse {
                        $exporter = new ProjectExportService;
                        $zipPath = $exporter->export($record);

                        Notification::make()
                            ->title('Proyecto exportado')
                            ->success()
                            ->send();

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(
                self::getUserProjectsPerPage()
            );
    }

    private static function getUserProjectsPerPage(): int
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->getPreference('projects_per_page', 10) ?? 10;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TasksRelationManager::class,
            RelationManagers\ExpensesRelationManager::class,
            RelationManagers\FilesRelationManager::class,
            RelationManagers\DiaryEntriesRelationManager::class,
            RelationManagers\LinksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}

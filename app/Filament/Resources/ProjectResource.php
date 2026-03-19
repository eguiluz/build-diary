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

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.projects');
    }

    public static function getModelLabel(): string
    {
        return __('app.project.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.project.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('app.project.section_basic'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('app.project.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label(__('app.project.slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\MarkdownEditor::make('description')
                            ->label(__('app.project.description'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label(__('app.project.category'))
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.project.section_status'))
                    ->schema([
                        Forms\Components\Select::make('status_id')
                            ->label(__('app.project.status'))
                            ->relationship('status', 'name')
                            ->required()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label(__('app.project.person'))
                            ->relationship('person', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('person_reason')
                            ->label(__('app.project.person_reason'))
                            ->options(Project::personReasons())
                            ->visible(fn ($get) => $get('person_id') !== null)
                            ->native(false),
                        Forms\Components\ToggleButtons::make('priority')
                            ->label(__('app.project.priority'))
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
                            ->label(__('app.project.is_archived')),
                        Forms\Components\Toggle::make('is_public')
                            ->label(__('app.project.is_public'))
                            ->helperText(__('app.project.is_public_helper'))
                            ->live(),
                        Forms\Components\Placeholder::make('public_url')
                            ->label(__('app.project.public_url'))
                            ->content(fn ($record) => $record?->slug ? url('/p/'.$record->slug) : '-')
                            ->visible(fn ($get) => $get('is_public'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.project.section_dates'))
                    ->schema([
                        Forms\Components\DatePicker::make('due_date')
                            ->label(__('app.project.due_date')),
                        Forms\Components\DatePicker::make('started_at')
                            ->label(__('app.project.started_at')),
                        Forms\Components\DatePicker::make('completed_at')
                            ->label(__('app.project.completed_at')),
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
                    ->label(__('app.project.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label(__('app.project.status'))
                    ->badge()
                    ->color(fn ($record) => $record->status->color ?? 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('app.project.category'))
                    ->badge()
                    ->color(fn ($record) => $record->category->color ?? 'gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label(__('app.person.label'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('app.project.due_date'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('app.project.priority'))
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
                    ->label(__('app.project.is_archived'))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_public')
                    ->label(__('app.project.is_public'))
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('app.common.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('app.common.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label(__('app.project.status'))
                    ->relationship('status', 'name'),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('app.project.category'))
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_archived')
                    ->label(__('app.project.is_archived')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                    ->label(__('app.project.export'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Project $record): BinaryFileResponse {
                        $exporter = new ProjectExportService;
                        $zipPath = $exporter->export($record);

                        Notification::make()
                            ->title(__('app.project.exported'))
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

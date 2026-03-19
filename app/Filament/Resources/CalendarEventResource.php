<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarEventResource\Pages;
use App\Models\CalendarEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CalendarEventResource extends Resource
{
    protected static ?string $model = CalendarEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.projects');
    }

    public static function getNavigationLabel(): string
    {
        return __('app.calendar_event.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('app.calendar_event.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.calendar_event.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('app.calendar_event.section_info'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('app.calendar_event.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('app.calendar_event.description'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label(__('app.calendar_event.type'))
                            ->required()
                            ->options([
                                'deadline' => __('app.calendar_event.types.deadline'),
                                'birthday' => __('app.calendar_event.types.birthday'),
                                'custom' => __('app.calendar_event.types.custom'),
                                'reminder' => __('app.calendar_event.types.reminder'),
                            ]),
                        Forms\Components\ColorPicker::make('color')
                            ->label(__('app.calendar_event.color')),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.calendar_event.section_datetime'))
                    ->schema([
                        Forms\Components\DatePicker::make('event_date')
                            ->label(__('app.calendar_event.event_date'))
                            ->required(),
                        Forms\Components\TimePicker::make('event_time')
                            ->label(__('app.calendar_event.event_time')),
                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('app.calendar_event.end_date')),
                        Forms\Components\Toggle::make('is_all_day')
                            ->label(__('app.calendar_event.is_all_day'))
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.calendar_event.section_associations'))
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('app.project.label'))
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label(__('app.person.label'))
                            ->relationship('person', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.calendar_event.section_reminder'))
                    ->schema([
                        Forms\Components\Toggle::make('reminder_enabled')
                            ->label(__('app.calendar_event.reminder_enable')),
                        Forms\Components\TextInput::make('reminder_minutes_before')
                            ->label(__('app.calendar_event.reminder_minutes_before'))
                            ->numeric()
                            ->default(60),
                    ])->columns(2),

                Forms\Components\Section::make(__('app.calendar_event.section_recurrence'))
                    ->schema([
                        Forms\Components\Toggle::make('is_recurring')
                            ->label(__('app.calendar_event.is_recurring_event')),
                        Forms\Components\TextInput::make('recurrence_rule')
                            ->label(__('app.calendar_event.recurrence_rule'))
                            ->helperText('Ej: FREQ=WEEKLY;INTERVAL=1'),
                    ])->columns(2)->collapsed(),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('app.calendar_event.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('app.calendar_event.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'deadline' => __('app.calendar_event.types.deadline'),
                        'birthday' => __('app.calendar_event.types.birthday'),
                        'custom' => __('app.calendar_event.types.custom'),
                        'reminder' => __('app.calendar_event.types.reminder'),
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('event_date')
                    ->label(__('app.calendar_event.event_date'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_time')
                    ->label(__('app.calendar_event.event_time'))
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('project.title')
                    ->label(__('app.project.label'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label(__('app.person.label'))
                    ->toggleable(),
                Tables\Columns\IconColumn::make('reminder_enabled')
                    ->label(__('app.calendar_event.reminder_enabled'))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('app.calendar_event.type'))
                    ->options([
                        'deadline' => __('app.calendar_event.types.deadline'),
                        'birthday' => __('app.calendar_event.types.birthday'),
                        'custom' => __('app.calendar_event.types.custom'),
                        'reminder' => __('app.calendar_event.types.reminder'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('event_date', 'asc');
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
            'index' => Pages\ListCalendarEvents::route('/'),
            'create' => Pages\CreateCalendarEvent::route('/create'),
            'edit' => Pages\EditCalendarEvent::route('/{record}/edit'),
        ];
    }
}

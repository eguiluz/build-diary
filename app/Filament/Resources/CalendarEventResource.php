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

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?string $navigationLabel = 'Lista de eventos';

    protected static ?string $modelLabel = 'Evento';

    protected static ?string $pluralModelLabel = 'Eventos';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del evento')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->required()
                            ->options([
                                'deadline' => 'Fecha límite',
                                'birthday' => 'Cumpleaños',
                                'custom' => 'Personalizado',
                                'reminder' => 'Recordatorio',
                            ]),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color'),
                    ])->columns(2),

                Forms\Components\Section::make('Fecha y hora')
                    ->schema([
                        Forms\Components\DatePicker::make('event_date')
                            ->label('Fecha')
                            ->required(),
                        Forms\Components\TimePicker::make('event_time')
                            ->label('Hora'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Fecha de fin'),
                        Forms\Components\Toggle::make('is_all_day')
                            ->label('Todo el día')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Asociaciones')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Proyecto')
                            ->relationship('project', 'title')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label('Persona')
                            ->relationship('person', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Recordatorio')
                    ->schema([
                        Forms\Components\Toggle::make('reminder_enabled')
                            ->label('Activar recordatorio'),
                        Forms\Components\TextInput::make('reminder_minutes_before')
                            ->label('Minutos antes')
                            ->numeric()
                            ->default(60),
                    ])->columns(2),

                Forms\Components\Section::make('Recurrencia')
                    ->schema([
                        Forms\Components\Toggle::make('is_recurring')
                            ->label('Evento recurrente'),
                        Forms\Components\TextInput::make('recurrence_rule')
                            ->label('Regla de recurrencia')
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
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'deadline' => 'Fecha límite',
                        'birthday' => 'Cumpleaños',
                        'custom' => 'Personalizado',
                        'reminder' => 'Recordatorio',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_time')
                    ->label('Hora')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('project.title')
                    ->label('Proyecto')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Persona')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('reminder_enabled')
                    ->label('Recordatorio')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'deadline' => 'Fecha límite',
                        'birthday' => 'Cumpleaños',
                        'custom' => 'Personalizado',
                        'reminder' => 'Recordatorio',
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

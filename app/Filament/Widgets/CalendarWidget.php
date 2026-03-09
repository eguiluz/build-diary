<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\CalendarEvent;
use App\Models\Person;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = CalendarEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function config(): array
    {
        return [
            'firstDay' => 1, // Lunes
            'locale' => 'es',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            ],
            'buttonText' => [
                'today' => 'Hoy',
                'month' => 'Mes',
                'week' => 'Semana',
                'day' => 'Día',
                'list' => 'Lista',
            ],
            'navLinks' => true,
            'editable' => true,
            'selectable' => true,
            'dayMaxEvents' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $info
     * @return array<int, array<string, mixed>>
     */
    public function fetchEvents(array $info): array
    {
        return CalendarEvent::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($info) {
                $query->whereBetween('event_date', [$info['start'], $info['end']])
                    ->orWhereBetween('end_date', [$info['start'], $info['end']]);
            })
            ->get()
            ->map(fn (CalendarEvent $event) => [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_date->format('Y-m-d').($event->event_time ? 'T'.$event->event_time : ''),
                'end' => $event->end_date?->format('Y-m-d'),
                'allDay' => $event->is_all_day,
                'color' => $event->color ?? $this->getColorByType($event->type),
                'extendedProps' => [
                    'type' => $event->type,
                    'description' => $event->description,
                ],
            ])
            ->toArray();
    }

    protected function getColorByType(string $type): string
    {
        return match ($type) {
            'deadline' => '#EF4444',
            'birthday' => '#EC4899',
            'reminder' => '#3B82F6',
            'custom' => '#10B981',
            default => '#6B7280',
        };
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Descripción')
                ->rows(3),

            Grid::make(2)
                ->schema([
                    Select::make('type')
                        ->label('Tipo')
                        ->required()
                        ->options([
                            'deadline' => 'Fecha límite',
                            'birthday' => 'Cumpleaños',
                            'custom' => 'Personalizado',
                            'reminder' => 'Recordatorio',
                        ])
                        ->default('custom'),

                    ColorPicker::make('color')
                        ->label('Color'),
                ]),

            Grid::make(2)
                ->schema([
                    DatePicker::make('event_date')
                        ->label('Fecha')
                        ->required(),

                    TimePicker::make('event_time')
                        ->label('Hora'),
                ]),

            Grid::make(2)
                ->schema([
                    DatePicker::make('end_date')
                        ->label('Fecha de fin'),

                    Toggle::make('is_all_day')
                        ->label('Todo el día')
                        ->default(true),
                ]),

            Grid::make(2)
                ->schema([
                    Select::make('project_id')
                        ->label('Proyecto')
                        ->relationship('project', 'title', fn ($query) => $query->where('user_id', Auth::id()))
                        ->searchable()
                        ->preload(),

                    Select::make('person_id')
                        ->label('Persona')
                        ->options(fn () => Person::where('user_id', Auth::id())->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                ]),

            Grid::make(2)
                ->schema([
                    Toggle::make('reminder_enabled')
                        ->label('Recordatorio'),

                    TextInput::make('reminder_minutes_before')
                        ->label('Minutos antes')
                        ->numeric()
                        ->default(60)
                        ->visible(fn ($get) => $get('reminder_enabled')),
                ]),
        ];
    }

    /**
     * @return array<int, \Filament\Actions\Action>
     */
    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo evento')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::id();

                    return $data;
                }),
        ];
    }

    /**
     * @return array<int, \Filament\Actions\Action>
     */
    protected function modalActions(): array
    {
        return [
            EditAction::make()
                ->label('Editar'),
            DeleteAction::make()
                ->label('Eliminar'),
        ];
    }

    public function eventDidMount(): string
    {
        return <<<'JS'
            function({ event, el }) {
                el.setAttribute('title', event.extendedProps?.description || event.title);
            }
        JS;
    }
}

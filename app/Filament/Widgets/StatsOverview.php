<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\CalendarEvent;
use App\Models\DiaryEntry;
use App\Models\Person;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Proyectos activos', Project::whereHas('status', fn ($q) => $q->where('is_completed', false))->count())
                ->description('En progreso')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('success'),

            Stat::make('Proyectos totales', Project::count())
                ->description('Todos los proyectos')
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make('Personas', Person::count())
                ->description('Contactos registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Entradas de diario', DiaryEntry::count())
                ->description('Total de registros')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Eventos próximos', CalendarEvent::where('event_date', '>=', now())->where('event_date', '<=', now()->addDays(7))->count())
                ->description('Próximos 7 días')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger'),

            Stat::make('Horas trabajadas', number_format(DiaryEntry::sum('time_spent_minutes') / 60, 1))
                ->description('Total registrado')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
        ];
    }
}

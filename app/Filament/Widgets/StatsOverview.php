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
            Stat::make(__('app.widgets.stats.active_projects'), Project::whereHas('status', fn ($q) => $q->where('is_completed', false))->count())
                ->description(__('app.widgets.stats.active_projects_desc'))
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('success'),

            Stat::make(__('app.widgets.stats.total_projects'), Project::count())
                ->description(__('app.widgets.stats.total_projects_desc'))
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make(__('app.widgets.stats.people'), Person::count())
                ->description(__('app.widgets.stats.people_desc'))
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make(__('app.widgets.stats.diary_entries'), DiaryEntry::count())
                ->description(__('app.widgets.stats.diary_entries_desc'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make(__('app.widgets.stats.upcoming_events'), CalendarEvent::where('event_date', '>=', now())->where('event_date', '<=', now()->addDays(7))->count())
                ->description(__('app.widgets.stats.upcoming_events_desc'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger'),

            Stat::make(__('app.widgets.stats.hours_worked'), number_format(DiaryEntry::sum('time_spent_minutes') / 60, 1))
                ->description(__('app.widgets.stats.hours_worked_desc'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),
        ];
    }
}

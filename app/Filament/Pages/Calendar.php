<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\CalendarWidget;
use Filament\Pages\Page;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.calendar';

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?string $navigationLabel = 'Vista Calendario';

    protected static ?string $title = 'Calendario';

    protected static ?int $navigationSort = 10;

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}

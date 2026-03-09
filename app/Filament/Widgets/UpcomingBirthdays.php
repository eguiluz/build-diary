<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Person;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingBirthdays extends BaseWidget
{
    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    protected static ?string $heading = 'Próximos cumpleaños';

    public function table(Table $table): Table
    {
        $today = now();
        $people = Person::query()
            ->whereNotNull('birthday')
            ->get()
            ->filter(function ($person) use ($today) {
                $bday = $person->birthday;
                if (! $bday) {
                    return false;
                }
                $bdayMonthDay = $bday->format('md');
                $todayMonthDay = $today->format('md');

                return $bdayMonthDay >= $todayMonthDay;
            })
            ->sortBy(fn ($person) => $person->birthday->format('md'))
            ->take(5);

        return $table
            ->query(fn () => $people)
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('birthday')
                    ->label('Cumpleaños')
                    ->date('d M'),
            ])
            ->paginated(false);
    }
}

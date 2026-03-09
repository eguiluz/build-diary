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
            ->query(Person::query()->whereNotNull('birthday'))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('birthday')
                    ->label('Cumpleaños')
                    ->date('d M'),
            ])
            ->paginated(false);
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person>|\Illuminate\Contracts\Pagination\Paginator<int, \App\Models\Person>|\Illuminate\Contracts\Pagination\CursorPaginator<int, \App\Models\Person> */
    public function getTableRecords(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Contracts\Pagination\CursorPaginator
    {
        $today = now();

        return Person::query()
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
            ->take(5)
            ->values();
    }
}

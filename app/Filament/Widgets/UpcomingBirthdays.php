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
        return $table
            ->query(
                Person::query()
                    ->whereNotNull('birthday')
                    ->whereRaw("DATE_FORMAT(birthday, '%m-%d') >= DATE_FORMAT(NOW(), '%m-%d')")
                    ->orderByRaw("DATE_FORMAT(birthday, '%m-%d')")
                    ->limit(5)
            )
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

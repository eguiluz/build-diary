<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\InventoryItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LowStockItems extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Alertas de stock';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryItem::query()
                    ->where('user_id', Auth::id())
                    ->where(function (Builder $query): void {
                        $query->lowStock()
                            ->orWhere('is_lent', true);
                    })
                    ->orderByRaw('CASE WHEN quantity <= 0 THEN 0 WHEN quantity <= min_quantity THEN 1 WHEN is_lent = 1 THEN 2 ELSE 3 END')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=f59e0b&color=fff')
                    ->size(32),
                Tables\Columns\TextColumn::make('name')
                    ->label('Artículo')
                    ->description(fn ($record) => $record->location),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->formatStateUsing(fn ($state) => InventoryItem::categories()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'tool' => 'warning',
                        'material' => 'primary',
                        'consumable' => 'gray',
                        'equipment' => 'success',
                        'safety' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Stock')
                    ->numeric(decimalPlaces: 0)
                    ->suffix(fn ($record) => $record->unit ? ' '.$record->unit : '')
                    ->color(fn ($record) => $record->isOutOfStock() ? 'danger' : ($record->isLowStock() ? 'warning' : null))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('min_quantity')
                    ->label('Mínimo')
                    ->numeric(decimalPlaces: 0)
                    ->suffix(fn ($record) => $record->unit ? ' '.$record->unit : '')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->state(function ($record) {
                        if ($record->isOutOfStock()) {
                            return 'Sin stock';
                        }
                        if ($record->isLowStock()) {
                            return 'Stock bajo';
                        }
                        if ($record->is_lent) {
                            return 'Prestado a '.$record->lent_to;
                        }

                        return 'OK';
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        str_contains((string) $state, 'Sin stock') => 'danger',
                        str_contains((string) $state, 'Stock bajo') => 'warning',
                        str_contains((string) $state, 'Prestado') => 'info',
                        default => 'success',
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('Todo en orden')
            ->emptyStateDescription('No hay items con stock bajo ni prestados.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }

    public static function canView(): bool
    {
        return InventoryItem::query()
            ->where('user_id', Auth::id())
            ->where(function (Builder $query): void {
                $query->lowStock()
                    ->orWhere('is_lent', true);
            })
            ->exists();
    }
}

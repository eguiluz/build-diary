<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryItemResource\Pages;
use App\Filament\Resources\InventoryItemResource\RelationManagers;
use App\Models\InventoryItem;
use App\Models\InventoryLoan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Taller';

    protected static ?string $modelLabel = 'Artículo';

    protected static ?string $pluralModelLabel = 'Inventario';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información básica')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\Select::make('category')
                            ->label('Categoría')
                            ->options(InventoryItem::categories())
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('condition')
                            ->label('Estado')
                            ->options(InventoryItem::conditions())
                            ->default('good')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Stock')
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->step(0.01)
                            ->required(),
                        Forms\Components\TextInput::make('unit')
                            ->label('Unidad')
                            ->placeholder('uds, m, kg, l...')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('min_quantity')
                            ->label('Cantidad mínima')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText('Alerta cuando baje de este valor'),
                        Forms\Components\TextInput::make('location')
                            ->label('Ubicación')
                            ->placeholder('Estantería A, Cajón 3...')
                            ->maxLength(255),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Detalles del producto')
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Marca')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('model')
                            ->label('Modelo')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('serial_number')
                            ->label('Número de serie')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purchase_price')
                            ->label('Precio de compra')
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Fecha de compra'),
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagen')
                            ->image()
                            ->directory('inventory')
                            ->maxSize(2048)
                            ->columnSpan(2),
                    ])
                    ->columns(4)
                    ->collapsed(),

                Forms\Components\Section::make('Préstamo')
                    ->schema([
                        Forms\Components\Toggle::make('is_lent')
                            ->label('Prestado')
                            ->reactive(),
                        Forms\Components\TextInput::make('lent_to')
                            ->label('Prestado a')
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_lent')),
                        Forms\Components\DatePicker::make('lent_at')
                            ->label('Fecha de préstamo')
                            ->visible(fn ($get) => $get('is_lent')),
                    ])
                    ->columns(3)
                    ->collapsed(),

                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas adicionales')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=f59e0b&color=fff')
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->brand ? "{$record->brand} {$record->model}" : null),
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
                    ->weight(fn ($record) => $record->isLowStock() ? 'bold' : null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => InventoryItem::conditions()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'new' => 'success',
                        'good' => 'primary',
                        'fair' => 'warning',
                        'poor' => 'danger',
                        'broken' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('location')
                    ->label('Ubicación')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_lent')
                    ->label('Prestado')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-right-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('Precio')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(InventoryItem::categories()),
                Tables\Filters\SelectFilter::make('condition')
                    ->label('Estado')
                    ->options(InventoryItem::conditions()),
                Tables\Filters\TernaryFilter::make('is_lent')
                    ->label('Préstamo')
                    ->placeholder('Todos')
                    ->trueLabel('Prestados')
                    ->falseLabel('Disponibles'),
                Tables\Filters\Filter::make('low_stock')
                    ->label('Stock bajo')
                    ->query(fn (Builder $query) => $query->whereNotNull('min_quantity')
                        ->whereColumn('quantity', '<=', 'min_quantity')),
            ])
            ->actions([
                Tables\Actions\Action::make('ajustar')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->label('Ajustar')
                    ->tooltip('Ajustar stock')
                    ->form([
                        Forms\Components\TextInput::make('adjustment')
                            ->label('Ajuste de cantidad')
                            ->numeric()
                            ->required()
                            ->helperText('Use valores negativos para restar'),
                    ])
                    ->action(function (InventoryItem $record, array $data): void {
                        $record->update([
                            'quantity' => max(0, (float) $record->quantity + (float) $data['adjustment']),
                        ]);
                    }),
                Tables\Actions\Action::make('prestar')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->label('Prestar')
                    ->tooltip('Prestar')
                    ->visible(fn ($record) => ! $record->is_lent)
                    ->form([
                        Forms\Components\TextInput::make('borrower_name')
                            ->label('Prestar a')
                            ->required(),
                        Forms\Components\TextInput::make('borrower_contact')
                            ->label('Contacto (teléfono, email...)')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('expected_return_at')
                            ->label('Devolución esperada'),
                        Forms\Components\Select::make('condition_at_loan')
                            ->label('Estado actual del artículo')
                            ->options(InventoryItem::conditions())
                            ->default(fn ($record) => $record->condition)
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2),
                    ])
                    ->action(function (InventoryItem $record, array $data): void {
                        // Create loan record
                        InventoryLoan::create([
                            'inventory_item_id' => $record->id,
                            'user_id' => $record->user_id,
                            'borrower_name' => $data['borrower_name'],
                            'borrower_contact' => $data['borrower_contact'] ?? null,
                            'lent_at' => now(),
                            'expected_return_at' => $data['expected_return_at'] ?? null,
                            'condition_at_loan' => $data['condition_at_loan'] ?? null,
                            'notes' => $data['notes'] ?? null,
                        ]);

                        // Update item status
                        $record->update([
                            'is_lent' => true,
                            'lent_to' => $data['borrower_name'],
                            'lent_at' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('devolver')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('success')
                    ->label('Devolver')
                    ->tooltip('Devolver')
                    ->visible(fn ($record) => $record->is_lent)
                    ->form([
                        Forms\Components\Select::make('condition_at_return')
                            ->label('Estado al devolver')
                            ->options(InventoryItem::conditions())
                            ->native(false),
                        Forms\Components\Textarea::make('return_notes')
                            ->label('Notas de devolución')
                            ->rows(2),
                    ])
                    ->action(function (InventoryItem $record, array $data): void {
                        // Find active loan and close it
                        $activeLoan = $record->loans()->whereNull('returned_at')->latest()->first();
                        if ($activeLoan) {
                            $notes = $activeLoan->notes;
                            if (! empty($data['return_notes'])) {
                                $notes = $notes ? $notes."\n\nDevolución: ".$data['return_notes'] : $data['return_notes'];
                            }

                            $activeLoan->update([
                                'returned_at' => now(),
                                'condition_at_return' => $data['condition_at_return'] ?? null,
                                'notes' => $notes,
                            ]);
                        }

                        // Update item status
                        $record->update([
                            'is_lent' => false,
                            'lent_to' => null,
                            'lent_at' => null,
                            'condition' => $data['condition_at_return'] ?? $record->condition,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Sin artículos en inventario')
            ->emptyStateDescription('Añade herramientas, materiales y equipos de tu taller.')
            ->emptyStateIcon('heroicon-o-wrench-screwdriver');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LoansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryItems::route('/'),
            'create' => Pages\CreateInventoryItem::route('/create'),
            'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<InventoryItem>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }
}

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

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('app.navigation.workshop');
    }

    public static function getModelLabel(): string
    {
        return __('app.inventory_item.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('app.inventory_item.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('app.inventory_item.section_basic'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('app.inventory_item.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Forms\Components\Select::make('category')
                            ->label(__('app.inventory_item.category'))
                            ->options(InventoryItem::categories())
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('condition')
                            ->label(__('app.inventory_item.condition'))
                            ->options(InventoryItem::conditions())
                            ->default('good')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('description')
                            ->label(__('app.inventory_item.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make(__('app.inventory_item.section_stock'))
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->label(__('app.inventory_item.quantity'))
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->step(0.01)
                            ->required(),
                        Forms\Components\TextInput::make('unit')
                            ->label(__('app.inventory_item.unit'))
                            ->placeholder('uds, m, kg, l...')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('min_quantity')
                            ->label(__('app.inventory_item.min_quantity'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->helperText(__('app.inventory_item.min_quantity_helper')),
                        Forms\Components\TextInput::make('location')
                            ->label(__('app.inventory_item.location'))
                            ->placeholder('Estantería A, Cajón 3...')
                            ->maxLength(255),
                    ])
                    ->columns(4),

                Forms\Components\Section::make(__('app.inventory_item.section_details'))
                    ->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label(__('app.inventory_item.brand'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('model')
                            ->label(__('app.inventory_item.model_field'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('serial_number')
                            ->label(__('app.inventory_item.serial_number'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purchase_price')
                            ->label(__('app.inventory_item.purchase_price'))
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label(__('app.inventory_item.purchase_date')),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('app.inventory_item.image'))
                            ->image()
                            ->directory('inventory')
                            ->maxSize(2048)
                            ->columnSpan(2),
                    ])
                    ->columns(4)
                    ->collapsed(),

                Forms\Components\Section::make(__('app.inventory_item.section_loan'))
                    ->schema([
                        Forms\Components\Toggle::make('is_lent')
                            ->label(__('app.inventory_item.is_lent'))
                            ->reactive(),
                        Forms\Components\TextInput::make('lent_to')
                            ->label(__('app.inventory_item.lent_to'))
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_lent')),
                        Forms\Components\DatePicker::make('lent_at')
                            ->label(__('app.inventory_item.lent_at'))
                            ->visible(fn ($get) => $get('is_lent')),
                    ])
                    ->columns(3)
                    ->collapsed(),

                Forms\Components\Section::make(__('app.inventory_item.section_notes'))
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('app.inventory_item.notes'))
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
                    ->label(__('app.inventory_item.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->brand ? "{$record->brand} {$record->model}" : null),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('app.inventory_item.category'))
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
                    ->label(__('app.inventory_item.stock_label'))
                    ->numeric(decimalPlaces: 0)
                    ->suffix(fn ($record) => $record->unit ? ' '.$record->unit : '')
                    ->color(fn ($record) => $record->isOutOfStock() ? 'danger' : ($record->isLowStock() ? 'warning' : null))
                    ->weight(fn ($record) => $record->isLowStock() ? 'bold' : null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->label(__('app.inventory_item.condition'))
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
                    ->label(__('app.inventory_item.location'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_lent')
                    ->label(__('app.inventory_item.is_lent'))
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-right-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label(__('app.inventory_item.price'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('app.inventory_item.category'))
                    ->options(InventoryItem::categories()),
                Tables\Filters\SelectFilter::make('condition')
                    ->label(__('app.inventory_item.condition'))
                    ->options(InventoryItem::conditions()),
                Tables\Filters\TernaryFilter::make('is_lent')
                    ->label(__('app.inventory_item.loan_filter'))
                    ->placeholder(__('app.inventory_item.filter_all'))
                    ->trueLabel(__('app.inventory_item.filter_lent'))
                    ->falseLabel(__('app.inventory_item.filter_available')),
                Tables\Filters\Filter::make('low_stock')
                    ->label(__('app.inventory_item.low_stock_filter'))
                    ->query(fn (Builder $query) => $query->whereNotNull('min_quantity')
                        ->whereColumn('quantity', '<=', 'min_quantity')),
            ])
            ->actions([
                Tables\Actions\Action::make('ajustar')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->label(__('app.inventory_item.action_adjust'))
                    ->tooltip(__('app.inventory_item.action_adjust_tooltip'))
                    ->form([
                        Forms\Components\TextInput::make('adjustment')
                            ->label(__('app.inventory_item.adjustment_field'))
                            ->numeric()
                            ->required()
                            ->helperText(__('app.inventory_item.adjustment_helper')),
                    ])
                    ->action(function (InventoryItem $record, array $data): void {
                        $record->update([
                            'quantity' => max(0, (float) $record->quantity + (float) $data['adjustment']),
                        ]);
                    }),
                Tables\Actions\Action::make('prestar')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->label(__('app.inventory_item.action_lend'))
                    ->tooltip(__('app.inventory_item.action_lend'))
                    ->visible(fn ($record) => ! $record->is_lent)
                    ->form([
                        Forms\Components\TextInput::make('borrower_name')
                            ->label(__('app.inventory_item.borrower_name'))
                            ->required(),
                        Forms\Components\TextInput::make('borrower_contact')
                            ->label(__('app.inventory_item.borrower_contact'))
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('expected_return_at')
                            ->label(__('app.inventory_item.expected_return')),
                        Forms\Components\Select::make('condition_at_loan')
                            ->label(__('app.inventory_item.condition_at_loan'))
                            ->options(InventoryItem::conditions())
                            ->default(fn ($record) => $record->condition)
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label(__('app.inventory_item.section_notes'))
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
                    ->label(__('app.inventory_item.action_return'))
                    ->tooltip(__('app.inventory_item.action_return'))
                    ->visible(fn ($record) => $record->is_lent)
                    ->form([
                        Forms\Components\Select::make('condition_at_return')
                            ->label(__('app.inventory_item.condition_at_return'))
                            ->options(InventoryItem::conditions())
                            ->native(false),
                        Forms\Components\Textarea::make('return_notes')
                            ->label(__('app.inventory_item.return_notes'))
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
            ->emptyStateHeading(__('app.inventory_item.empty_heading'))
            ->emptyStateDescription(__('app.inventory_item.empty_desc'))
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

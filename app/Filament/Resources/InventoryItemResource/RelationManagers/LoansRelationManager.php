<?php

declare(strict_types=1);

namespace App\Filament\Resources\InventoryItemResource\RelationManagers;

use App\Models\InventoryItem;
use App\Models\InventoryLoan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LoansRelationManager extends RelationManager
{
    protected static string $relationship = 'loans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('borrower_name')
                    ->label(__('app.loan.borrower_name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('borrower_contact')
                    ->label(__('app.loan.borrower_contact'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('lent_at')
                    ->label(__('app.loan.lent_at'))
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('expected_return_at')
                    ->label(__('app.loan.expected_return_at')),
                Forms\Components\DatePicker::make('returned_at')
                    ->label(__('app.loan.returned_at')),
                Forms\Components\Select::make('condition_at_loan')
                    ->label(__('app.loan.condition_at_loan'))
                    ->options(InventoryItem::conditions())
                    ->native(false),
                Forms\Components\Select::make('condition_at_return')
                    ->label(__('app.loan.condition_at_return'))
                    ->options(InventoryItem::conditions())
                    ->native(false),
                Forms\Components\Textarea::make('notes')
                    ->label(__('app.loan.notes'))
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('borrower_name')
            ->columns([
                Tables\Columns\TextColumn::make('borrower_name')
                    ->label(__('app.loan.borrower'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('borrower_contact')
                    ->label(__('app.loan.borrower_contact'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lent_at')
                    ->label(__('app.loan.lent_at_short'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_return_at')
                    ->label(__('app.loan.expected_return_at'))
                    ->date('d/m/Y')
                    ->color(fn (InventoryLoan $record) => $record->isOverdue() ? 'danger' : null)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('returned_at')
                    ->label(__('app.loan.returned_at_short'))
                    ->date('d/m/Y')
                    ->placeholder(__('app.loan.pending'))
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label(__('app.loan.duration_days'))
                    ->suffix(__('app.loan.duration_days_suffix'))
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('condition_at_loan')
                    ->label(__('app.loan.condition_initial'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? InventoryItem::conditions()[$state] ?? $state : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('condition_at_return')
                    ->label(__('app.loan.condition_final'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? InventoryItem::conditions()[$state] ?? $state : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('returned_at')
                    ->label(__('app.loan.filter_status'))
                    ->placeholder(__('app.loan.filter_all'))
                    ->trueLabel(__('app.loan.filter_returned'))
                    ->falseLabel(__('app.loan.filter_pending'))
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('returned_at'),
                        false: fn ($query) => $query->whereNull('returned_at'),
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('app.loan.new_loan'))
                    ->mutateFormDataUsing(function (array $data): array {
                        /** @var InventoryItem $ownerRecord */
                        $ownerRecord = $this->getOwnerRecord();
                        $data['user_id'] = $ownerRecord->user_id;

                        return $data;
                    })
                    ->after(function (): void {
                        /** @var InventoryItem $item */
                        $item = $this->getOwnerRecord();
                        $item->update([
                            'is_lent' => true,
                            'lent_to' => $item->loans()->whereNull('returned_at')->latest()->first()?->borrower_name,
                            'lent_at' => now(),
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('return')
                    ->label(__('app.loan.return_action'))
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('success')
                    ->visible(fn (InventoryLoan $record) => $record->isActive())
                    ->form([
                        Forms\Components\DatePicker::make('returned_at')
                            ->label(__('app.loan.returned_at'))
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('condition_at_return')
                            ->label(__('app.loan.condition_at_return'))
                            ->options(InventoryItem::conditions())
                            ->native(false),
                        Forms\Components\Textarea::make('return_notes')
                            ->label(__('app.loan.return_notes'))
                            ->rows(2),
                    ])
                    ->action(function (InventoryLoan $record, array $data): void {
                        $notes = $record->notes;
                        if (! empty($data['return_notes'])) {
                            $notes = $notes ? $notes."\n\nDevolución: ".$data['return_notes'] : $data['return_notes'];
                        }

                        $record->update([
                            'returned_at' => $data['returned_at'],
                            'condition_at_return' => $data['condition_at_return'],
                            'notes' => $notes,
                        ]);

                        /** @var InventoryItem $item */
                        $item = $this->getOwnerRecord();

                        // Check if there are more active loans
                        $activeLoansCount = $item->loans()->whereNull('returned_at')->count();
                        if ($activeLoansCount === 0) {
                            $item->update([
                                'is_lent' => false,
                                'lent_to' => null,
                                'lent_at' => null,
                            ]);
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('lent_at', 'desc')
            ->emptyStateHeading(__('app.loan.empty_heading'))
            ->emptyStateDescription(__('app.loan.empty_desc'))
            ->emptyStateIcon('heroicon-o-arrow-right-circle');
    }
}

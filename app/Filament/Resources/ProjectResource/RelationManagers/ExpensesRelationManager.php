<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Project;
use App\Models\ProjectExpense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    protected static ?string $icon = 'heroicon-o-currency-euro';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('app.expense.section_title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('app.expense.name'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label(__('app.expense.description'))
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Select::make('category')
                    ->label(__('app.expense.category'))
                    ->options(ProjectExpense::categories())
                    ->default('material')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('app.expense.quantity'))
                    ->numeric()
                    ->default(1)
                    ->minValue(0.01)
                    ->step(0.01)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => $set('calculated_total', $this->calculateTotal($get('quantity'), $get('unit_price')))),
                Forms\Components\TextInput::make('unit')
                    ->label(__('app.expense.unit'))
                    ->placeholder('m, kg, uds, etc.')
                    ->maxLength(20),
                Forms\Components\TextInput::make('unit_price')
                    ->label(__('app.expense.unit_price'))
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01)
                    ->minValue(0)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => $set('calculated_total', $this->calculateTotal($get('quantity'), $get('unit_price')))),
                Forms\Components\Placeholder::make('calculated_total')
                    ->label(__('app.expense.total_estimated'))
                    ->content(fn (Forms\Get $get): string => number_format((float) $get('quantity') * (float) $get('unit_price'), 2, ',', '.').' €'),
                Forms\Components\TextInput::make('supplier')
                    ->label(__('app.expense.supplier'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->label(__('app.expense.link'))
                    ->url()
                    ->maxLength(2048),
                Forms\Components\DatePicker::make('purchased_at')
                    ->label(__('app.expense.purchase_date')),
                Forms\Components\Toggle::make('is_purchased')
                    ->label(__('app.expense.is_purchased'))
                    ->default(false),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->poll('10s')
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_purchased')
                    ->label('')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update([
                            'purchased_at' => $state ? now() : null,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('app.expense.material'))
                    ->searchable()
                    ->description(fn ($record) => $record->description)
                    ->wrap()
                    ->extraAttributes(fn ($record) => $record->is_purchased ? ['class' => 'line-through opacity-60'] : []),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('app.expense.category'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProjectExpense::categories()[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'material' => 'primary',
                        'tool' => 'warning',
                        'consumable' => 'gray',
                        'service' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('app.expense.quantity'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(fn ($record) => $record->unit ? ' '.$record->unit : ''),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('app.expense.unit_price_short'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' €'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('app.public.total'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' €')
                    ->weight('bold')
                    ->color(fn ($record) => $record->is_purchased ? 'success' : null)
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('app.expense.total_budget_label'))
                            ->numeric(decimalPlaces: 2)
                            ->suffix(' €'),
                    ]),
                Tables\Columns\TextColumn::make('supplier')
                    ->label(__('app.expense.supplier'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('app.expense.category'))
                    ->options(ProjectExpense::categories()),
                Tables\Filters\TernaryFilter::make('is_purchased')
                    ->label(__('app.expense.status'))
                    ->placeholder(__('app.expense.filter_all'))
                    ->trueLabel(__('app.expense.purchased'))
                    ->falseLabel(__('app.expense.pending_filter')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('app.expense.add_expense'))
                    ->modalHeading(__('app.expense.add_expense_heading')),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->icon(fn ($record) => $record->is_purchased ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_purchased ? 'warning' : 'success')
                    ->tooltip(fn ($record) => $record->is_purchased ? __('app.expense.mark_pending_tooltip') : __('app.expense.mark_purchased_tooltip'))
                    ->action(function ($record) {
                        $record->update([
                            'is_purchased' => ! $record->is_purchased,
                            'purchased_at' => ! $record->is_purchased ? now() : null,
                        ]);
                    }),
                Tables\Actions\Action::make('openUrl')
                    ->icon('heroicon-o-link')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => filled($record->url))
                    ->tooltip(__('app.expense.open_link')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markPurchased')
                        ->label(__('app.expense.bulk_mark_purchased'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($r) => $r->update(['is_purchased' => true, 'purchased_at' => now()])))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('markPending')
                        ->label(__('app.expense.bulk_mark_pending'))
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->action(fn ($records) => $records->each(fn ($r) => $r->update(['is_purchased' => false, 'purchased_at' => null])))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('app.expense.empty_heading'))
            ->emptyStateDescription(__('app.expense.empty_desc'))
            ->emptyStateIcon('heroicon-o-currency-euro')
            ->contentFooter(view('filament.resources.project-resource.expenses-footer', $this->getBudgetData()));
    }

    /**
     * @return array{totalBudget: float, spentBudget: float, pendingBudget: float}
     */
    private function getBudgetData(): array
    {
        /** @var Project $project */
        $project = $this->getOwnerRecord();

        return [
            'totalBudget' => $project->total_budget,
            'spentBudget' => $project->spent_budget,
            'pendingBudget' => $project->pending_budget,
        ];
    }

    private function calculateTotal(mixed $quantity, mixed $unitPrice): string
    {
        $total = (float) $quantity * (float) $unitPrice;

        return number_format($total, 2, ',', '.').' €';
    }
}

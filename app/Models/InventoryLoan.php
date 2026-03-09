<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Builder<InventoryLoan> active()
 * @method static Builder<InventoryLoan> returned()
 */
class InventoryLoan extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'user_id',
        'borrower_name',
        'borrower_contact',
        'lent_at',
        'expected_return_at',
        'returned_at',
        'notes',
        'condition_at_loan',
        'condition_at_return',
    ];

    protected $casts = [
        'lent_at' => 'date',
        'expected_return_at' => 'date',
        'returned_at' => 'date',
    ];

    /**
     * @return BelongsTo<InventoryItem, $this>
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->returned_at === null;
    }

    public function isOverdue(): bool
    {
        if (! $this->isActive() || $this->expected_return_at === null) {
            return false;
        }

        return $this->expected_return_at->isPast();
    }

    public function getDurationDaysAttribute(): ?int
    {
        $endDate = $this->returned_at ?? now();

        return (int) $this->lent_at->diffInDays($endDate);
    }

    /**
     * @param  Builder<InventoryLoan>  $query
     * @return Builder<InventoryLoan>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('returned_at');
    }

    /**
     * @param  Builder<InventoryLoan>  $query
     * @return Builder<InventoryLoan>
     */
    public function scopeReturned(Builder $query): Builder
    {
        return $query->whereNotNull('returned_at');
    }
}

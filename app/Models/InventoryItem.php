<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @method static Builder<InventoryItem> lowStock()
 * @method static Builder<InventoryItem> lent()
 */
class InventoryItem extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'category',
        'quantity',
        'unit',
        'min_quantity',
        'location',
        'brand',
        'model',
        'serial_number',
        'purchase_price',
        'purchase_date',
        'condition',
        'image',
        'notes',
        'is_lent',
        'lent_to',
        'lent_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_quantity' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'is_lent' => 'boolean',
        'lent_at' => 'date',
    ];

    // Categories
    public const CATEGORY_TOOL = 'tool';

    public const CATEGORY_MATERIAL = 'material';

    public const CATEGORY_CONSUMABLE = 'consumable';

    public const CATEGORY_EQUIPMENT = 'equipment';

    public const CATEGORY_SAFETY = 'safety';

    public const CATEGORY_OTHER = 'other';

    // Conditions
    public const CONDITION_NEW = 'new';

    public const CONDITION_GOOD = 'good';

    public const CONDITION_FAIR = 'fair';

    public const CONDITION_POOR = 'poor';

    public const CONDITION_BROKEN = 'broken';

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_TOOL => 'Herramienta',
            self::CATEGORY_MATERIAL => 'Material',
            self::CATEGORY_CONSUMABLE => 'Consumible',
            self::CATEGORY_EQUIPMENT => 'Equipamiento',
            self::CATEGORY_SAFETY => 'Seguridad',
            self::CATEGORY_OTHER => 'Otro',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function conditions(): array
    {
        return [
            self::CONDITION_NEW => 'Nuevo',
            self::CONDITION_GOOD => 'Bueno',
            self::CONDITION_FAIR => 'Regular',
            self::CONDITION_POOR => 'Malo',
            self::CONDITION_BROKEN => 'Roto',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InventoryItem $item): void {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name).'-'.Str::random(6);
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<InventoryLoan, $this>
     */
    public function loans(): HasMany
    {
        return $this->hasMany(InventoryLoan::class);
    }

    /**
     * @return HasMany<InventoryLoan, $this>
     */
    public function activeLoans(): HasMany
    {
        return $this->loans()->whereNull('returned_at');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->category] ?? $this->category;
    }

    public function getConditionLabelAttribute(): string
    {
        return self::conditions()[$this->condition] ?? $this->condition;
    }

    public function isLowStock(): bool
    {
        if ($this->min_quantity === null) {
            return false;
        }

        return (float) $this->quantity <= (float) $this->min_quantity;
    }

    public function isOutOfStock(): bool
    {
        return (float) $this->quantity <= 0;
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereNotNull('min_quantity')
            ->whereColumn('quantity', '<=', 'min_quantity');
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeLent(Builder $query): Builder
    {
        return $query->where('is_lent', true);
    }
}

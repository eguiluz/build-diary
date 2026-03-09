<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectExpense extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'category',
        'quantity',
        'unit',
        'unit_price',
        'supplier',
        'url',
        'purchased_at',
        'is_purchased',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'purchased_at' => 'date',
        'is_purchased' => 'boolean',
    ];

    public const CATEGORY_MATERIAL = 'material';

    public const CATEGORY_TOOL = 'tool';

    public const CATEGORY_CONSUMABLE = 'consumable';

    public const CATEGORY_SERVICE = 'service';

    public const CATEGORY_OTHER = 'other';

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_MATERIAL => 'Material',
            self::CATEGORY_TOOL => 'Herramienta',
            self::CATEGORY_CONSUMABLE => 'Consumible',
            self::CATEGORY_SERVICE => 'Servicio',
            self::CATEGORY_OTHER => 'Otro',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categories()[$this->category] ?? $this->category;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProjectLinkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property string $url
 * @property string $type
 * @property string|null $description
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Project $project
 */
final class ProjectLink extends Model
{
    /** @use HasFactory<ProjectLinkFactory> */
    use HasFactory;

    public const TYPE_REFERENCE = 'reference';

    public const TYPE_TUTORIAL = 'tutorial';

    public const TYPE_RESOURCE = 'resource';

    public const TYPE_SHOP = 'shop';

    public const TYPE_DOCUMENTATION = 'documentation';

    protected $fillable = [
        'project_id',
        'title',
        'url',
        'type',
        'description',
        'order',
    ];

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param  Builder<ProjectLink>  $query
     * @return Builder<ProjectLink>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getDomainAttribute(): string
    {
        return parse_url($this->url, PHP_URL_HOST) ?? $this->url;
    }

    /**
     * @return array<string, string>
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_REFERENCE => 'Referencia',
            self::TYPE_TUTORIAL => 'Tutorial',
            self::TYPE_RESOURCE => 'Recurso',
            self::TYPE_SHOP => 'Tienda',
            self::TYPE_DOCUMENTATION => 'Documentación',
        ];
    }
}

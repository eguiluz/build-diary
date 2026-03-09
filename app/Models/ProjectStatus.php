<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProjectStatusFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $color
 * @property int $order
 * @property bool $is_default
 * @property bool $is_completed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Project> $projects
 */
final class ProjectStatus extends Model
{
    /** @use HasFactory<ProjectStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
        'is_default',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'status_id');
    }

    /**
     * @param  Builder<ProjectStatus>  $query
     * @return Builder<ProjectStatus>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * @param  Builder<ProjectStatus>  $query
     * @return Builder<ProjectStatus>
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }
}

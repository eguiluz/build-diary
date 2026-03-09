<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string $color
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Project> $projects
 */
class ProjectCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProjectCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'category_id');
    }
}

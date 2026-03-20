<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DiaryEntryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $project_id
 * @property string|null $title
 * @property string $content
 * @property string $type
 * @property \Carbon\Carbon $entry_date
 * @property \Carbon\Carbon|null $entry_time
 * @property int|null $time_spent_minutes
 * @property array<string, mixed>|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DiaryEntryImage> $images
 */
final class DiaryEntry extends Model
{
    /** @use HasFactory<DiaryEntryFactory> */
    use HasFactory;

    public const TYPE_NOTE = 'note';

    public const TYPE_PROGRESS = 'progress';

    public const TYPE_MILESTONE = 'milestone';

    public const TYPE_ISSUE = 'issue';

    public const TYPE_SOLUTION = 'solution';

    protected $fillable = [
        'project_id',
        'title',
        'content',
        'type',
        'entry_date',
        'entry_time',
        'time_spent_minutes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'entry_time' => 'datetime:H:i',
            'metadata' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<DiaryEntryImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(DiaryEntryImage::class)->orderBy('order');
    }

    /**
     * @param  Builder<DiaryEntry>  $query
     * @return Builder<DiaryEntry>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * @param  Builder<DiaryEntry>  $query
     * @return Builder<DiaryEntry>
     */
    public function scopeOnDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('entry_date', $date);
    }

    /**
     * @param  Builder<DiaryEntry>  $query
     * @return Builder<DiaryEntry>
     */
    public function scopeBetweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    public function getTimeSpentFormattedAttribute(): ?string
    {
        if (! $this->time_spent_minutes) {
            return null;
        }

        $hours = intdiv($this->time_spent_minutes, 60);
        $minutes = $this->time_spent_minutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * @return array<string, string>
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_NOTE => __('app.public.entry_types.note'),
            self::TYPE_PROGRESS => __('app.public.entry_types.progress'),
            self::TYPE_MILESTONE => __('app.public.entry_types.milestone'),
            self::TYPE_ISSUE => __('app.public.entry_types.issue'),
            self::TYPE_SOLUTION => __('app.public.entry_types.solution'),
        ];
    }
}

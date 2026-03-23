<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasTags;
use App\Observers\ProjectObserver;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $status_id
 * @property int|null $category_id
 * @property int|null $person_id
 * @property string|null $person_reason
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property \Carbon\Carbon|null $due_date
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $completed_at
 * @property int $priority
 * @property bool $is_archived
 * @property bool $is_public
 * @property string $theme
 * @property array<string, mixed>|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read ProjectStatus $status
 * @property-read ProjectCategory|null $category
 * @property-read Person|null $person
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProjectFile> $files
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DiaryEntry> $diaryEntries
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProjectLink> $links
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CalendarEvent> $calendarEvents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $tags
 */
#[ObservedBy([ProjectObserver::class])]
final class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use HasTags;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status_id',
        'category_id',
        'person_id',
        'person_reason',
        'title',
        'slug',
        'description',
        'due_date',
        'started_at',
        'completed_at',
        'priority',
        'is_archived',
        'is_public',
        'theme',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'started_at' => 'date',
            'completed_at' => 'date',
            'is_archived' => 'boolean',
            'is_public' => 'boolean',
            'metadata' => 'array',
        ];
    }

    // ========== Person Reasons ==========

    public const REASON_BIRTHDAY = 'birthday';

    public const REASON_GIFT = 'gift';

    public const REASON_COMMISSION = 'commission';

    public const REASON_DEDICATION = 'dedication';

    public const REASON_OTHER = 'other';

    // ========== Themes ==========

    public const THEME_DEFAULT = 'default';

    public const THEME_MINIMAL = 'minimal';

    public const THEME_WORKSHOP = 'workshop';

    public const THEME_BLUEPRINT = 'blueprint';

    public const THEME_MADERA = 'madera';

    /**
     * @return array<string, string>
     */
    public static function themes(): array
    {
        return [
            self::THEME_DEFAULT => __('app.project.themes.default'),
            self::THEME_MINIMAL => __('app.project.themes.minimal'),
            self::THEME_WORKSHOP => __('app.project.themes.workshop'),
            self::THEME_BLUEPRINT => __('app.project.themes.blueprint'),
            self::THEME_MADERA => __('app.project.themes.madera'),
        ];
    }

    // ========== Priorities ==========

    public const PRIORITY_LOW = 1;

    public const PRIORITY_MEDIUM = 2;

    public const PRIORITY_HIGH = 3;

    /**
     * @return array<int, string>
     */
    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
        ];
    }

    public function getPriorityLabelAttribute(): ?string
    {
        if ($this->priority === 0) {
            return null;
        }

        return self::priorities()[$this->priority] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public static function personReasons(): array
    {
        return [
            self::REASON_BIRTHDAY => 'Cumpleaños',
            self::REASON_GIFT => 'Regalo',
            self::REASON_COMMISSION => 'Encargo',
            self::REASON_DEDICATION => 'Dedicatoria',
            self::REASON_OTHER => 'Otro',
        ];
    }

    public function getPersonReasonLabelAttribute(): ?string
    {
        if ($this->person_reason === null) {
            return null;
        }

        return self::personReasons()[$this->person_reason] ?? $this->person_reason;
    }

    // ========== Relationships ==========

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ProjectStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    /**
     * @return BelongsTo<ProjectCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    /**
     * @return BelongsTo<Person, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @return HasMany<ProjectFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->orderBy('order');
    }

    /**
     * @return HasMany<DiaryEntry, $this>
     */
    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class)->orderByDesc('entry_date');
    }

    /**
     * @return HasMany<ProjectLink, $this>
     */
    public function links(): HasMany
    {
        return $this->hasMany(ProjectLink::class)->orderBy('order');
    }

    /**
     * @return HasMany<ProjectTask, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class)->orderBy('order');
    }

    /**
     * @return HasMany<ProjectExpense, $this>
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }

    /**
     * @return HasMany<CalendarEvent, $this>
     */
    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    // ========== Scopes ==========

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeWithStatus(Builder $query, int $statusId): Builder
    {
        return $query->where('status_id', $statusId);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeDueBefore(Builder $query, string $date): Builder
    {
        return $query->whereNotNull('due_date')->where('due_date', '<=', $date);
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->dueBefore(now()->toDateString())
            ->whereHas('status', fn ($q) => $q->where('is_completed', false));
    }

    // ========== Accessors ==========

    public function getIsOverdueAttribute(): bool
    {
        if (! $this->due_date || $this->status->is_completed) {
            return false;
        }

        return $this->due_date->isPast();
    }

    public function getTotalTimeSpentAttribute(): int
    {
        return (int) $this->diaryEntries()->sum('time_spent_minutes');
    }

    public function getTasksProgressAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->tasks()->where('is_completed', true)->count();

        return (int) round(($completed / $total) * 100);
    }

    public function getTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tasks()->where('is_completed', true)->count();
    }

    public function getTotalBudgetAttribute(): float
    {
        return (float) $this->expenses()->sum('total_price');
    }

    public function getSpentBudgetAttribute(): float
    {
        return (float) $this->expenses()->where('is_purchased', true)->sum('total_price');
    }

    public function getPendingBudgetAttribute(): float
    {
        return (float) $this->expenses()->where('is_purchased', false)->sum('total_price');
    }

    public function getBudgetProgressAttribute(): int
    {
        $total = $this->total_budget;
        if ($total === 0.0) {
            return 0;
        }

        return (int) round(($this->spent_budget / $total) * 100);
    }
}

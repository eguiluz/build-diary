<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CalendarEventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $project_id
 * @property int|null $person_id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property \Carbon\Carbon $event_date
 * @property string|null $event_time
 * @property \Carbon\Carbon|null $end_date
 * @property bool $is_all_day
 * @property bool $is_recurring
 * @property string|null $recurrence_rule
 * @property string|null $color
 * @property bool $reminder_enabled
 * @property int|null $reminder_minutes_before
 * @property \Carbon\Carbon|null $reminder_sent_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 * @property-read Project|null $project
 * @property-read Person|null $person
 */
final class CalendarEvent extends Model
{
    /** @use HasFactory<CalendarEventFactory> */
    use HasFactory;

    public const TYPE_DEADLINE = 'deadline';

    public const TYPE_BIRTHDAY = 'birthday';

    public const TYPE_CUSTOM = 'custom';

    public const TYPE_REMINDER = 'reminder';

    public const TYPE_PROJECT_START = 'project_start';

    public const TYPE_PROJECT_DUE = 'project_due';

    public const TYPE_PROJECT_COMPLETED = 'project_completed';

    protected $fillable = [
        'user_id',
        'project_id',
        'person_id',
        'title',
        'description',
        'type',
        'event_date',
        'event_time',
        'end_date',
        'is_all_day',
        'is_recurring',
        'recurrence_rule',
        'color',
        'reminder_enabled',
        'reminder_minutes_before',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'end_date' => 'date',
            'is_all_day' => 'boolean',
            'is_recurring' => 'boolean',
            'reminder_enabled' => 'boolean',
            'reminder_sent_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<Person, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    public function scopeBetweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('event_date', [$startDate, $endDate])
                ->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->whereNotNull('end_date')
                        ->where('event_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                });
        });
    }

    /**
     * @param  Builder<CalendarEvent>  $query
     * @return Builder<CalendarEvent>
     */
    public function scopeUpcoming(Builder $query, int $days = 30): Builder
    {
        return $query->where('event_date', '>=', now())
            ->where('event_date', '<=', now()->addDays($days))
            ->orderBy('event_date');
    }

    /**
     * @return array<string, string>
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_DEADLINE => __('app.calendar_event.types.deadline'),
            self::TYPE_BIRTHDAY => __('app.calendar_event.types.birthday'),
            self::TYPE_CUSTOM => __('app.calendar_event.types.custom'),
            self::TYPE_REMINDER => __('app.calendar_event.types.reminder'),
        ];
    }
}

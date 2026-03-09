<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasTags;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property \Carbon\Carbon|null $birthday
 * @property bool $birthday_reminder
 * @property int $reminder_days_before
 * @property \Carbon\Carbon|null $last_birthday_reminder_sent_at
 * @property string|null $notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Project> $projects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CalendarEvent> $calendarEvents
 */
final class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory;

    use HasTags;
    use SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'birthday',
        'birthday_reminder',
        'reminder_days_before',
        'last_birthday_reminder_sent_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'birthday_reminder' => 'boolean',
            'last_birthday_reminder_sent_at' => 'datetime',
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
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return HasMany<CalendarEvent, $this>
     */
    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }

    /**
     * @param  Builder<Person>  $query
     * @return Builder<Person>
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param  Builder<Person>  $query
     * @return Builder<Person>
     */
    public function scopeWithBirthdayReminder(Builder $query): Builder
    {
        return $query->where('birthday_reminder', true)->whereNotNull('birthday');
    }

    /**
     * @param  Builder<Person>  $query
     * @return Builder<Person>
     */
    public function scopeUpcomingBirthdays(Builder $query, int $days = 30): Builder
    {
        $today = now();
        $endDate = now()->addDays($days);

        return $query->whereNotNull('birthday')
            ->whereRaw('DAYOFYEAR(birthday) BETWEEN DAYOFYEAR(?) AND DAYOFYEAR(?)', [
                $today->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ]);
    }

    public function getNextBirthdayAttribute(): ?\Carbon\Carbon
    {
        if (! $this->birthday) {
            return null;
        }

        $birthday = $this->birthday->copy()->year(now()->year);

        if ($birthday->isPast()) {
            $birthday->addYear();
        }

        return $birthday;
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthday?->age;
    }
}

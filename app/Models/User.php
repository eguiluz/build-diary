<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property array<string, mixed>|null $preferences
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Project> $projects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person> $people
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tag> $tags
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CalendarEvent> $calendarEvents
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // All authenticated users can access admin panel
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
        ];
    }

    // ========== Theme Constants ==========

    public const THEME_LIGHT = 'light';

    public const THEME_DARK = 'dark';

    public const THEME_SYSTEM = 'system';

    /**
     * @return array<string, string>
     */
    public static function themes(): array
    {
        return [
            self::THEME_LIGHT => 'Claro',
            self::THEME_DARK => 'Oscuro',
            self::THEME_SYSTEM => 'Sistema',
        ];
    }

    // ========== Preferences ==========

    /**
     * @return array<string, mixed>
     */
    public function getDefaultPreferences(): array
    {
        return [
            'theme' => self::THEME_SYSTEM,
            'sidebar_collapsed' => false,
            'projects_per_page' => 10,
            'show_completed_tasks' => true,
            'email_notifications' => true,
        ];
    }

    public function getPreference(string $key, mixed $default = null): mixed
    {
        $preferences = $this->preferences ?? [];
        $defaults = $this->getDefaultPreferences();

        return $preferences[$key] ?? $defaults[$key] ?? $default;
    }

    public function setPreference(string $key, mixed $value): void
    {
        $preferences = $this->preferences ?? [];
        $preferences[$key] = $value;
        $this->preferences = $preferences;
        $this->save();
    }

    public function getTheme(): string
    {
        return $this->getPreference('theme', self::THEME_SYSTEM);
    }

    // ========== Relationships ==========

    /**
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return HasMany<Person, $this>
     */
    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /**
     * @return HasMany<Tag, $this>
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * @return HasMany<CalendarEvent, $this>
     */
    public function calendarEvents(): HasMany
    {
        return $this->hasMany(CalendarEvent::class);
    }
}

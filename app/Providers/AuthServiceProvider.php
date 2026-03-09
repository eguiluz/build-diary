<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CalendarEvent;
use App\Models\DiaryEntry;
use App\Models\Person;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\Tag;
use App\Policies\CalendarEventPolicy;
use App\Policies\DiaryEntryPolicy;
use App\Policies\PersonPolicy;
use App\Policies\ProjectFilePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Person::class => PersonPolicy::class,
        Tag::class => TagPolicy::class,
        CalendarEvent::class => CalendarEventPolicy::class,
        DiaryEntry::class => DiaryEntryPolicy::class,
        ProjectFile::class => ProjectFilePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

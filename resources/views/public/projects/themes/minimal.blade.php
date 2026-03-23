<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $project->title }} - {{ config('app.name', 'Build Diary') }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($project->description), 160) }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($project->description), 160) }}">
    <meta property="og:type" content="article">

    @include('partials.favicon')

    <!-- Fonts -->
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=lora:400,400i,600,700&family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Lora', Georgia, serif; }
        .sans { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>

<body class="min-h-screen bg-white text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">

    <!-- Top bar -->
    <div class="border-b border-zinc-100 dark:border-zinc-800">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-6 py-4">
            <a class="sans flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100" href="{{ url('/') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Build Diary
            </a>
            <div class="sans flex items-center gap-4">
                <a class="text-sm text-zinc-400 transition-colors hover:text-zinc-700 dark:hover:text-zinc-200" href="{{ route('public.gallery', $project->user) }}">
                    {{ __('app.public.view_more_projects') }}
                </a>
                <a class="text-sm font-medium text-zinc-700 underline underline-offset-4 transition-colors hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100" href="{{ route('public.project.pdf', $project->slug) }}">
                    {{ __('app.public.download_pdf') }}
                </a>
                <a class="text-sm font-medium text-zinc-700 underline underline-offset-4 transition-colors hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100" href="{{ route('public.project.zip', $project->slug) }}">
                    {{ __('app.public.download_zip') }}
                </a>
            </div>
        </div>
    </div>

    <main class="mx-auto max-w-3xl px-6 py-16">
        <article>
            <!-- Header -->
            <header class="mb-16 border-b border-zinc-100 pb-16 dark:border-zinc-800">

                <!-- Meta badges -->
                <div class="sans mb-6 flex flex-wrap items-center gap-2">
                    @if ($project->status)
                        <span class="text-xs font-medium uppercase tracking-widest" style="color: {{ $project->status->color }}">
                            {{ $project->status->name }}
                        </span>
                    @endif
                    @if ($project->status && $project->category)
                        <span class="text-zinc-300 dark:text-zinc-600">·</span>
                    @endif
                    @if ($project->category)
                        <span class="text-xs font-medium uppercase tracking-widest text-zinc-400">
                            {{ $project->category->name }}
                        </span>
                    @endif
                    @if ($project->priority && $project->priority > 0)
                        <span class="text-zinc-300 dark:text-zinc-600">·</span>
                        @php
                            $priorityLabel = match ($project->priority) {
                                1 => __('app.public.priority_low'),
                                2 => __('app.public.priority_medium'),
                                3 => __('app.public.priority_high'),
                                default => '',
                            };
                            $priorityColor = match ($project->priority) {
                                1 => '#22c55e',
                                2 => '#f59e0b',
                                3 => '#ef4444',
                                default => '#71717a',
                            };
                        @endphp
                        <span class="sans text-xs font-medium uppercase tracking-widest" style="color: {{ $priorityColor }}">
                            {{ $priorityLabel }}
                        </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight text-zinc-900 sm:text-5xl dark:text-zinc-50">
                    {{ $project->title }}
                </h1>

                <!-- Tags -->
                @if ($project->tags->count() > 0)
                    <div class="sans mb-8 flex flex-wrap gap-2">
                        @foreach ($project->tags as $tag)
                            <span class="rounded border px-2 py-0.5 text-xs font-medium" style="border-color: {{ $tag->color }}40; color: {{ $tag->color }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Dates -->
                <div class="sans flex flex-wrap gap-6 text-sm text-zinc-400">
                    @if ($project->started_at)
                        <span>{{ __('app.public.started') }}: <span class="text-zinc-600 dark:text-zinc-300">{{ $project->started_at->format('d M Y') }}</span></span>
                    @endif
                    @if ($project->due_date)
                        <span>{{ __('app.public.due_date') }}: <span class="text-zinc-600 dark:text-zinc-300">{{ $project->due_date->format('d M Y') }}</span></span>
                    @endif
                    @if ($project->completed_at)
                        <span>{{ __('app.public.completed') }}: <span class="text-zinc-700 font-medium dark:text-zinc-200">{{ $project->completed_at->format('d M Y') }}</span></span>
                    @endif
                </div>

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="sans mt-8 flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            @if ($project->person_reason)
                                {{ $project->person_reason_label }} {{ __('app.public.for') }}
                            @else
                                {{ __('app.public.dedicated_to') }}
                            @endif
                            <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $project->person->name }}</span>
                        </p>
                    </div>
                @endif
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-16">
                    @php $images = $project->files->where('type', 'image'); @endphp
                    @if ($images->count() === 1)
                        <figure class="overflow-hidden rounded-sm">
                            <a href="{{ $images->first()->url }}" target="_blank">
                                <img class="w-full object-cover" src="{{ $images->first()->url }}" alt="{{ $images->first()->description ?? $images->first()->original_name }}" loading="lazy">
                            </a>
                            @if ($images->first()->description)
                                <figcaption class="sans mt-2 text-center text-sm italic text-zinc-400">{{ $images->first()->description }}</figcaption>
                            @endif
                        </figure>
                    @else
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($images as $file)
                                <figure class="overflow-hidden rounded-sm">
                                    <a href="{{ $file->url }}" target="_blank">
                                        <img class="h-56 w-full object-cover transition-opacity hover:opacity-90" src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                    </a>
                                    @if ($file->description)
                                        <figcaption class="sans mt-1 text-sm italic text-zinc-400">{{ $file->description }}</figcaption>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

            <!-- Description -->
            @if ($project->description)
                <section class="mb-16">
                    <div class="prose prose-zinc dark:prose-invert prose-headings:font-bold prose-a:text-zinc-900 prose-a:underline dark:prose-a:text-zinc-200 max-w-none leading-relaxed">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </section>
            @endif

            <!-- Checklist -->
            @if ($project->tasks->count() > 0)
                <section class="mb-16 border-t border-zinc-100 pt-12 dark:border-zinc-800">
                    @php
                        $completedCount = $project->tasks->where('is_completed', true)->count();
                        $totalCount = $project->tasks->count();
                        $progress = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                    @endphp
                    <h2 class="sans mb-1 text-xs font-semibold uppercase tracking-widest text-zinc-400">{{ __('app.public.checklist') }}</h2>
                    <p class="mb-6 text-2xl font-bold">{{ $completedCount }} / {{ $totalCount }} {{ __('app.public.tasks') }}</p>

                    <div class="mb-8 h-1 w-full bg-zinc-100 dark:bg-zinc-800">
                        <div class="{{ $progress === 100 ? 'bg-zinc-900 dark:bg-zinc-100' : 'bg-zinc-400' }} h-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>

                    <ul class="space-y-3">
                        @foreach ($project->tasks as $task)
                            <li class="flex items-start gap-4">
                                <span class="mt-0.5 shrink-0 text-lg leading-none {{ $task->is_completed ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-200 dark:text-zinc-700' }}">
                                    {{ $task->is_completed ? '✓' : '○' }}
                                </span>
                                <div>
                                    <span class="sans {{ $task->is_completed ? 'text-zinc-400 line-through' : 'text-zinc-800 dark:text-zinc-200' }}">{{ $task->title }}</span>
                                    @if ($task->description)
                                        <p class="sans mt-0.5 text-sm italic text-zinc-400">{{ $task->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Budget -->
            @if ($project->expenses->count() > 0)
                <section class="mb-16 border-t border-zinc-100 pt-12 dark:border-zinc-800">
                    <h2 class="sans mb-1 text-xs font-semibold uppercase tracking-widest text-zinc-400">{{ __('app.public.budget') }}</h2>
                    <div class="mb-8 flex gap-8">
                        <div>
                            <p class="sans text-xs uppercase tracking-wide text-zinc-400">{{ __('app.public.total') }}</p>
                            <p class="text-2xl font-bold">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div>
                            <p class="sans text-xs uppercase tracking-wide text-zinc-400">{{ __('app.public.spent') }}</p>
                            <p class="text-2xl font-bold">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div>
                            <p class="sans text-xs uppercase tracking-wide text-zinc-400">{{ __('app.public.pending') }}</p>
                            <p class="text-2xl font-bold text-zinc-400">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
                        </div>
                    </div>

                    @php
                        $categories = [
                            'material' => ['name' => __('app.public.expense_categories.material'), 'icon' => '🧱'],
                            'tool' => ['name' => __('app.public.expense_categories.tool'), 'icon' => '🔧'],
                            'consumable' => ['name' => __('app.public.expense_categories.consumable'), 'icon' => '📦'],
                            'service' => ['name' => __('app.public.expense_categories.service'), 'icon' => '👷'],
                            'other' => ['name' => __('app.public.expense_categories.other'), 'icon' => '📋'],
                        ];
                        $groupedExpenses = $project->expenses->groupBy('category');
                    @endphp

                    <div class="space-y-6">
                        @foreach ($groupedExpenses as $category => $expenses)
                            @php
                                $categoryInfo = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '📦'];
                                $categoryTotal = $expenses->sum('total_price');
                            @endphp
                            <div>
                                <div class="sans mb-2 flex items-center justify-between border-b border-zinc-100 pb-2 dark:border-zinc-800">
                                    <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-400">{{ $categoryInfo['icon'] }} {{ $categoryInfo['name'] }}</span>
                                    <span class="text-sm text-zinc-400">{{ number_format($categoryTotal, 2, ',', '.') }} €</span>
                                </div>
                                <ul class="space-y-2">
                                    @foreach ($expenses as $expense)
                                        <li class="sans flex items-center justify-between py-1 text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="{{ $expense->is_purchased ? 'text-zinc-400' : 'text-zinc-500' }}">{{ $expense->is_purchased ? '✓' : '○' }}</span>
                                                <span class="{{ $expense->is_purchased ? 'text-zinc-400 line-through' : 'text-zinc-700 dark:text-zinc-300' }}">
                                                    {{ $expense->name }}
                                                    @if ($expense->supplier)
                                                        <span class="text-zinc-400">({{ $expense->supplier }})</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-zinc-400">{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €</span>
                                                <span class="{{ $expense->is_purchased ? 'text-zinc-500' : 'font-medium text-zinc-700 dark:text-zinc-300' }}">{{ number_format($expense->total_price, 2, ',', '.') }} €</span>
                                                @if ($expense->url)
                                                    <a href="{{ $expense->url }}" target="_blank" rel="noopener" class="text-zinc-400 underline hover:text-zinc-600">↗</a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Links -->
            @if ($project->links->count() > 0)
                <section class="mb-16 border-t border-zinc-100 pt-12 dark:border-zinc-800">
                    <h2 class="sans mb-6 text-xs font-semibold uppercase tracking-widest text-zinc-400">{{ __('app.public.links') }}</h2>
                    <ul class="space-y-3">
                        @foreach ($project->links as $link)
                            <li class="sans flex items-start gap-3">
                                <span class="mt-0.5 text-zinc-300 dark:text-zinc-600">→</span>
                                <div>
                                    <a class="font-medium text-zinc-800 underline underline-offset-4 hover:text-zinc-600 dark:text-zinc-200 dark:hover:text-zinc-400" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $link->title }}
                                    </a>
                                    @if ($link->description)
                                        <p class="mt-0.5 text-sm italic text-zinc-400">{{ $link->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Downloadable Files -->
            @if ($project->files->where('type', '!=', 'image')->count() > 0)
                <section class="mb-16 border-t border-zinc-100 pt-12 dark:border-zinc-800">
                    <h2 class="sans mb-6 text-xs font-semibold uppercase tracking-widest text-zinc-400">{{ __('app.public.downloadable_files') }}</h2>
                    <ul class="space-y-3">
                        @foreach ($project->files->where('type', '!=', 'image') as $file)
                            <li class="sans flex items-center justify-between py-2">
                                <div>
                                    <a class="font-medium text-zinc-800 underline underline-offset-4 hover:text-zinc-600 dark:text-zinc-200 dark:hover:text-zinc-400" href="{{ $file->url }}" target="_blank" download>
                                        {{ $file->name }}
                                    </a>
                                    <span class="ml-2 text-sm text-zinc-400">
                                        {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                    </span>
                                </div>
                                <a class="text-zinc-400 underline hover:text-zinc-700" href="{{ $file->url }}" title="{{ __('app.public.download') }}" download>↓</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Diary -->
            @if ($project->diaryEntries->count() > 0)
                <section class="border-t border-zinc-100 pt-12 dark:border-zinc-800">
                    <h2 class="sans mb-12 text-xs font-semibold uppercase tracking-widest text-zinc-400">{{ __('app.public.project_diary') }}</h2>

                    <div class="space-y-12">
                        @foreach ($project->diaryEntries as $entry)
                            <article class="grid gap-4 sm:grid-cols-[120px_1fr]">
                                <!-- Date -->
                                <div class="sans text-sm text-zinc-400">
                                    <time>{{ $entry->entry_date->format('d M Y') }}</time>
                                    @if ($entry->type)
                                        @php
                                            $typeLabel = match ($entry->type) {
                                                'progress' => __('app.public.entry_types.progress'),
                                                'issue' => __('app.public.entry_types.issue'),
                                                'solution' => __('app.public.entry_types.solution'),
                                                'milestone' => __('app.public.entry_types.milestone'),
                                                'note' => __('app.public.entry_types.note'),
                                                default => ucfirst($entry->type),
                                            };
                                        @endphp
                                        <p class="mt-1 text-xs italic">{{ $typeLabel }}</p>
                                    @endif
                                    @if ($entry->time_spent_minutes)
                                        <p class="mt-1 text-xs">
                                            {{ floor($entry->time_spent_minutes / 60) }}h {{ $entry->time_spent_minutes % 60 }}m
                                        </p>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div>
                                    @if ($entry->title)
                                        <h3 class="mb-3 text-lg font-bold text-zinc-900 dark:text-zinc-100">{{ $entry->title }}</h3>
                                    @endif
                                    <div class="prose prose-zinc prose-sm dark:prose-invert max-w-none">
                                        {!! $entry->content !!}
                                    </div>
                                    @if ($entry->images->count() > 0)
                                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                            @foreach ($entry->images as $image)
                                                <a href="{{ $image->url }}" target="_blank" class="block overflow-hidden rounded-sm">
                                                    <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy" class="h-28 w-full object-cover transition-opacity hover:opacity-90">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </article>

                            @if (!$loop->last)
                                <hr class="border-zinc-100 dark:border-zinc-800">
                            @endif
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer -->
    <footer class="sans mt-24 border-t border-zinc-100 dark:border-zinc-800">
        <div class="mx-auto max-w-3xl px-6 py-8">
            <p class="text-center text-sm text-zinc-400">{{ __('app.public.published_with') }}</p>
        </div>
    </footer>
</body>

</html>

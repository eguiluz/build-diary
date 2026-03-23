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
    <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,600,700&family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --accent: #a3e635;
            --accent-dim: #4d7c0f;
            --bg: #09090b;
            --surface: #111113;
            --surface-2: #18181b;
            --border: #27272a;
            --text: #e4e4e7;
            --text-muted: #71717a;
        }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        .mono { font-family: 'JetBrains Mono', ui-monospace, 'Courier New', monospace; }
        .accent { color: var(--accent); }
        .border-custom { border-color: var(--border); }
        .surface { background-color: var(--surface); }
        .surface-2 { background-color: var(--surface-2); }
        .text-muted { color: var(--text-muted); }
        .prose-workshop h1, .prose-workshop h2, .prose-workshop h3 { color: var(--accent); }
        .prose-workshop a { color: var(--accent); }
        .prose-workshop code { background: var(--surface-2); color: var(--accent); padding: 0.1em 0.3em; border-radius: 3px; }
        .prose-workshop pre { background: var(--surface-2) !important; border: 1px solid var(--border); }
        .prose-workshop blockquote { border-left-color: var(--accent); }
        .prose-workshop strong { color: #f4f4f5; }
        .progress-bar {
            background: linear-gradient(90deg, var(--accent) 0%, #65a30d 100%);
        }
        .tag-accent {
            background-color: rgba(163, 230, 53, 0.08);
            border: 1px solid rgba(163, 230, 53, 0.25);
            color: var(--accent);
        }
    </style>
</head>

<body class="min-h-screen antialiased">

    <!-- Top bar -->
    <header style="border-bottom: 1px solid var(--border);">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <a class="mono flex items-center gap-3 text-sm transition-opacity hover:opacity-70" href="{{ url('/') }}" style="color: var(--accent);">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                build-diary<span style="color: var(--text-muted);">:/</span>
            </a>

            <a class="text-sm transition-colors hover:opacity-70" style="color: var(--text-muted);" href="{{ route('public.gallery', $project->user) }}">
                {{ __('app.public.view_more_projects') }}
            </a>

            <div class="flex items-center gap-3">
                <a class="mono flex items-center gap-2 rounded px-4 py-2 text-sm font-semibold transition-opacity hover:opacity-80" style="background-color: var(--accent); color: #09090b;" href="{{ route('public.project.pdf', $project->slug) }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('app.public.download_pdf') }}
                </a>
                <a class="mono flex items-center gap-2 rounded border px-4 py-2 text-sm font-medium transition-opacity hover:opacity-80" style="border-color: var(--border); color: var(--text-muted);" href="{{ route('public.project.zip', $project->slug) }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('app.public.download_zip') }}
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-12">
        <article>
            <!-- Project Header -->
            <header class="mb-12">
                <!-- Status strip -->
                <div class="mono mb-6 flex flex-wrap items-center gap-4 text-xs" style="color: var(--text-muted);">
                    <span style="color: var(--accent);">▶ PROJECT</span>
                    @if ($project->status)
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $project->status->color }};"></span>
                            {{ strtoupper($project->status->name) }}
                        </span>
                    @endif
                    @if ($project->category)
                        <span>// {{ strtoupper($project->category->name) }}</span>
                    @endif
                    @if ($project->priority && $project->priority > 0)
                        @php
                            $priorityLabel = match ($project->priority) {
                                1 => 'PRIORITY:LOW',
                                2 => 'PRIORITY:MED',
                                3 => 'PRIORITY:HIGH',
                                default => '',
                            };
                            $priorityColor = match ($project->priority) {
                                1 => '#22c55e',
                                2 => '#f59e0b',
                                3 => '#ef4444',
                                default => '#71717a',
                            };
                        @endphp
                        <span style="color: {{ $priorityColor }};">{{ $priorityLabel }}</span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight sm:text-5xl" style="color: #f4f4f5;">
                    {{ $project->title }}
                </h1>

                <!-- Tags -->
                @if ($project->tags->count() > 0)
                    <div class="mb-6 flex flex-wrap gap-2">
                        @foreach ($project->tags as $tag)
                            <span class="mono rounded px-2 py-0.5 text-xs" style="background-color: {{ $tag->color }}15; border: 1px solid {{ $tag->color }}40; color: {{ $tag->color }};">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Dates -->
                <div class="mono flex flex-wrap gap-6 text-sm" style="color: var(--text-muted);">
                    @if ($project->started_at)
                        <span>START=<span style="color: var(--text);">{{ $project->started_at->format('Y-m-d') }}</span></span>
                    @endif
                    @if ($project->due_date)
                        <span>DUE=<span style="color: var(--text);">{{ $project->due_date->format('Y-m-d') }}</span></span>
                    @endif
                    @if ($project->completed_at)
                        <span>DONE=<span style="color: var(--accent);">{{ $project->completed_at->format('Y-m-d') }}</span></span>
                    @endif
                </div>

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="mono mt-8 flex items-center gap-3 rounded border p-4" style="border-color: var(--border); background-color: var(--surface);">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded font-bold" style="background-color: var(--accent); color: #09090b;">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs" style="color: var(--text-muted);">
                                @if ($project->person_reason)
                                    {{ strtoupper($project->person_reason_label) }} FOR
                                @else
                                    DEDICATED_TO
                                @endif
                            </p>
                            <p class="font-semibold" style="color: var(--text);">{{ $project->person->name }}</p>
                        </div>
                    </div>
                @endif
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-12">
                    <div class="mono mb-3 text-xs" style="color: var(--accent);">[ GALLERY ]</div>
                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($project->files->where('type', 'image') as $file)
                            <figure class="group relative overflow-hidden rounded border" style="border-color: var(--border);">
                                <a href="{{ $file->url }}" target="_blank">
                                    <img class="h-56 w-full object-cover opacity-80 transition-opacity duration-300 group-hover:opacity-100" src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                    <!-- Green overlay on hover -->
                                    <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-10" style="background: var(--accent);"></div>
                                </a>
                                @if ($file->description)
                                    <figcaption class="mono absolute inset-x-0 bottom-0 p-2 text-xs opacity-0 transition-opacity group-hover:opacity-100" style="background: rgba(9,9,11,0.85); color: var(--accent);">
                                        {{ $file->description }}
                                    </figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Description -->
            @if ($project->description)
                <section class="mb-12">
                    <div class="mono mb-3 text-xs" style="color: var(--accent);">[ DESCRIPTION ]</div>
                    <div class="prose-workshop prose prose-invert prose-sm max-w-none leading-relaxed" style="color: var(--text);">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </section>
            @endif

            <!-- Checklist -->
            @if ($project->tasks->count() > 0)
                <section class="mb-12">
                    @php
                        $completedCount = $project->tasks->where('is_completed', true)->count();
                        $totalCount = $project->tasks->count();
                        $progress = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                    @endphp
                    <div class="mono mb-4 flex items-center justify-between text-xs">
                        <span style="color: var(--accent);">[ CHECKLIST ]</span>
                        <span style="color: var(--text-muted);">{{ $completedCount }}/{{ $totalCount }} &mdash; {{ $progress }}%</span>
                    </div>

                    <!-- Progress -->
                    <div class="mb-6 h-1 w-full rounded" style="background-color: var(--surface-2);">
                        <div class="progress-bar h-full rounded transition-all duration-700" style="width: {{ $progress }}%;"></div>
                    </div>

                    <!-- Tasks -->
                    <ul class="mono space-y-2 text-sm">
                        @foreach ($project->tasks as $task)
                            <li class="flex items-start gap-3 py-1">
                                <span style="color: {{ $task->is_completed ? '#a3e635' : '#3f3f46' }}; flex-shrink: 0; margin-top: 1px;">
                                    {{ $task->is_completed ? '[✓]' : '[ ]' }}
                                </span>
                                <div>
                                    <span style="color: {{ $task->is_completed ? 'var(--text-muted)' : 'var(--text)' }}; {{ $task->is_completed ? 'text-decoration: line-through;' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                    @if ($task->description)
                                        <p class="mt-0.5 text-xs" style="color: var(--text-muted);">// {{ $task->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Budget -->
            @if ($project->expenses->count() > 0)
                <section class="mb-12">
                    <div class="mono mb-4 text-xs" style="color: var(--accent);">[ BUDGET ]</div>

                    <!-- Summary -->
                    <div class="mono mb-6 grid grid-cols-3 gap-3">
                        <div class="rounded border p-4" style="border-color: var(--border); background-color: var(--surface);">
                            <p class="mb-1 text-xs" style="color: var(--text-muted);">TOTAL</p>
                            <p class="text-xl font-bold" style="color: var(--text);">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="rounded border p-4" style="border-color: var(--border); background-color: var(--surface);">
                            <p class="mb-1 text-xs" style="color: var(--accent);">SPENT</p>
                            <p class="text-xl font-bold" style="color: var(--accent);">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="rounded border p-4" style="border-color: var(--border); background-color: var(--surface);">
                            <p class="mb-1 text-xs" style="color: var(--text-muted);">PENDING</p>
                            <p class="text-xl font-bold" style="color: var(--text-muted);">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
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

                    <div class="space-y-4">
                        @foreach ($groupedExpenses as $category => $expenses)
                            @php
                                $categoryInfo = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '📦'];
                                $categoryTotal = $expenses->sum('total_price');
                            @endphp
                            <details class="group rounded border" style="border-color: var(--border);">
                                <summary class="mono flex cursor-pointer list-none items-center justify-between p-3 text-sm" style="color: var(--text-muted);">
                                    <span class="flex items-center gap-2">
                                        <span style="color: var(--accent);">▶</span>
                                        {{ $categoryInfo['icon'] }} {{ strtoupper($categoryInfo['name']) }}
                                        <span class="rounded px-1.5 py-0.5 text-xs" style="background: var(--surface-2);">{{ $expenses->count() }}</span>
                                    </span>
                                    <span style="color: var(--text);">{{ number_format($categoryTotal, 2, ',', '.') }} €</span>
                                </summary>
                                <div class="border-t p-3" style="border-color: var(--border);">
                                    <ul class="mono space-y-2 text-sm">
                                        @foreach ($expenses as $expense)
                                            <li class="flex items-center justify-between py-1">
                                                <div class="flex items-center gap-3">
                                                    <span style="color: {{ $expense->is_purchased ? '#a3e635' : '#3f3f46' }};">
                                                        {{ $expense->is_purchased ? '[✓]' : '[ ]' }}
                                                    </span>
                                                    <span style="color: {{ $expense->is_purchased ? 'var(--text-muted)' : 'var(--text)' }}; {{ $expense->is_purchased ? 'text-decoration: line-through;' : '' }}">
                                                        {{ $expense->name }}
                                                        @if ($expense->supplier)
                                                            <span style="color: var(--text-muted);"> // {{ $expense->supplier }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span style="color: var(--text-muted);">{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €</span>
                                                    <span style="color: {{ $expense->is_purchased ? 'var(--accent)' : 'var(--text)' }}; min-width: 5rem; text-align: right;">{{ number_format($expense->total_price, 2, ',', '.') }} €</span>
                                                    @if ($expense->url)
                                                        <a href="{{ $expense->url }}" target="_blank" rel="noopener" style="color: var(--accent);">↗</a>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Links -->
            @if ($project->links->count() > 0)
                <section class="mb-12">
                    <div class="mono mb-4 text-xs" style="color: var(--accent);">[ LINKS ]</div>
                    <ul class="mono space-y-3">
                        @foreach ($project->links as $link)
                            <li class="flex items-start gap-3">
                                <span style="color: var(--accent); flex-shrink: 0;">→</span>
                                <div>
                                    <a class="font-medium transition-opacity hover:opacity-70" style="color: var(--accent);" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $link->title }}
                                    </a>
                                    @if ($link->description)
                                        <p class="mt-0.5 text-xs" style="color: var(--text-muted);">// {{ $link->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Downloadable Files -->
            @if ($project->files->where('type', '!=', 'image')->count() > 0)
                <section class="mb-12">
                    <div class="mono mb-4 text-xs" style="color: var(--accent);">[ FILES ]</div>
                    <ul class="mono space-y-2">
                        @foreach ($project->files->where('type', '!=', 'image') as $file)
                            <li class="flex items-center justify-between rounded border p-3 text-sm" style="border-color: var(--border); background: var(--surface);">
                                <div class="flex items-center gap-3">
                                    @if ($file->type === 'stl')
                                        <span style="color: #60a5fa;">◈</span>
                                    @elseif ($file->type === 'pdf')
                                        <span style="color: #f87171;">◈</span>
                                    @else
                                        <span style="color: var(--text-muted);">◈</span>
                                    @endif
                                    <div>
                                        <a class="font-medium transition-opacity hover:opacity-70" style="color: var(--accent);" href="{{ $file->url }}" target="_blank" download>
                                            {{ $file->name }}
                                        </a>
                                        <span class="ml-2 text-xs" style="color: var(--text-muted);">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                        </span>
                                    </div>
                                </div>
                                <a class="transition-opacity hover:opacity-70" style="color: var(--accent);" href="{{ $file->url }}" title="{{ __('app.public.download') }}" download>↓</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Diary / Build Log -->
            @if ($project->diaryEntries->count() > 0)
                <section class="mt-16">
                    <div class="mono mb-8 text-xs" style="color: var(--accent);">[ BUILD LOG ]</div>

                    <div class="relative">
                        <!-- Vertical line -->
                        <div class="absolute bottom-0 left-4 top-0 w-px" style="background: linear-gradient(to bottom, var(--accent), transparent);"></div>

                        <div class="space-y-8 pl-12">
                            @foreach ($project->diaryEntries as $entry)
                                <article class="relative">
                                    <!-- Node -->
                                    <div class="absolute -left-8 top-1 flex h-4 w-4 items-center justify-center rounded" style="background-color: var(--bg); border: 1px solid var(--accent);">
                                        <div class="h-1.5 w-1.5 rounded-sm" style="background: var(--accent);"></div>
                                    </div>

                                    <!-- Card -->
                                    <div class="rounded border p-5 transition-colors" style="border-color: var(--border); background-color: var(--surface);">
                                        <!-- Header -->
                                        <div class="mono mb-4 flex flex-wrap items-center gap-4 text-xs" style="color: var(--text-muted);">
                                            <time style="color: var(--accent);">{{ $entry->entry_date->format('Y-m-d') }}</time>
                                            @if ($entry->type)
                                                @php
                                                    $typeConfig = match ($entry->type) {
                                                        'progress' => ['label' => 'PROGRESS', 'color' => '#60a5fa'],
                                                        'issue' => ['label' => 'ISSUE', 'color' => '#f87171'],
                                                        'solution' => ['label' => 'SOLUTION', 'color' => '#4ade80'],
                                                        'milestone' => ['label' => 'MILESTONE', 'color' => '#c084fc'],
                                                        'note' => ['label' => 'NOTE', 'color' => '#a1a1aa'],
                                                        default => ['label' => strtoupper($entry->type), 'color' => '#a1a1aa'],
                                                    };
                                                @endphp
                                                <span style="color: {{ $typeConfig['color'] }};">{{ $typeConfig['label'] }}</span>
                                            @endif
                                            @if ($entry->time_spent_minutes)
                                                <span>TIME={{ floor($entry->time_spent_minutes / 60) }}h{{ $entry->time_spent_minutes % 60 > 0 ? $entry->time_spent_minutes % 60 . 'm' : '' }}</span>
                                            @endif
                                        </div>

                                        <!-- Title -->
                                        @if ($entry->title)
                                            <h3 class="mb-3 text-base font-bold" style="color: #f4f4f5;">{{ $entry->title }}</h3>
                                        @endif

                                        <!-- Content -->
                                        <div class="prose-workshop prose prose-invert prose-sm max-w-none" style="color: var(--text);">
                                            {!! $entry->content !!}
                                        </div>

                                        <!-- Images -->
                                        @if ($entry->images->count() > 0)
                                            <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                                @foreach ($entry->images as $image)
                                                    <a href="{{ $image->url }}" target="_blank" class="group block overflow-hidden rounded border" style="border-color: var(--border);">
                                                        <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy" class="h-28 w-full object-cover opacity-70 transition-opacity duration-300 group-hover:opacity-100">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer -->
    <footer class="mono mt-20 border-t py-8 text-center text-xs" style="border-color: var(--border); color: var(--text-muted);">
        <span style="color: var(--accent);">// </span>{{ __('app.public.published_with') }}
    </footer>
</body>

</html>

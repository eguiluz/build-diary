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
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700i&family=inter:300,400,500,600&family=space-mono:400,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0a0a0a;
            --ink-light: #1a1a1a;
            --graphite: #4a4a4a;
            --silver: #8a8a8a;
            --mist: #c5c5c5;
            --paper: #f7f6f3;
            --paper-dark: #eeede8;
            --rule: #d0cfc8;
            --accent: #1a1a1a;
            --accent-mark: #c41e1e;  /* Red markup pen */
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            font-weight: 300;
        }
        .serif { font-family: 'Cormorant Garamond', 'Georgia', serif; }
        .mono { font-family: 'Space Mono', ui-monospace, 'Courier New', monospace; }

        /* Thin hairline rule */
        .hairline { border-color: var(--rule); }

        /* Grid background (subtle blueprint grid) */
        .grid-bg {
            background-image:
                linear-gradient(rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.04) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Section label in the architectural annotation style */
        .section-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--silver);
        }
        .section-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            font-weight: 400;
            line-height: 1;
            color: var(--paper-dark);
            position: absolute;
            right: 0;
            top: -0.5rem;
        }

        /* Divider lines */
        .divider {
            border: none;
            border-top: 1px solid var(--rule);
            margin: 0;
        }
        .divider-heavy {
            border: none;
            border-top: 2px solid var(--ink);
            margin: 0;
        }

        /* Prose */
        .prose-arq h1, .prose-arq h2, .prose-arq h3 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--ink);
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        .prose-arq a { color: var(--ink); text-decoration: underline; text-underline-offset: 3px; }
        .prose-arq code {
            font-family: 'Space Mono', monospace;
            background: var(--paper-dark);
            color: var(--graphite);
            padding: 0.1em 0.3em;
            font-size: 0.8em;
        }
        .prose-arq pre { background: var(--paper-dark) !important; border-left: 2px solid var(--ink); }
        .prose-arq blockquote {
            border-left: 2px solid var(--ink);
            color: var(--graphite);
            font-style: italic;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1em;
        }
        .prose-arq strong { color: var(--ink); font-weight: 600; }
        .prose-arq p { line-height: 1.85; color: var(--graphite); }

        /* Tag pill */
        .tag-arq {
            font-family: 'Space Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 3px 8px;
            border: 1px solid;
        }

        /* Progress bar */
        .progress-bar { background: var(--ink); }

        /* Red annotation mark */
        .mark-red { color: var(--accent-mark); }

        /* Dimension line style for dates */
        .dim-line {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--silver);
        }
        .dim-line::before, .dim-line::after {
            content: '';
            height: 1px;
            width: 20px;
            background: var(--mist);
        }
    </style>
</head>

<body class="min-h-screen antialiased">

    <!-- Top bar -->
    <header style="background: var(--paper); border-bottom: 2px solid var(--ink);">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-8 py-5">
            <a class="flex items-center gap-3 transition-opacity hover:opacity-60" href="{{ url('/') }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color: var(--ink);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="mono text-xs font-bold uppercase tracking-widest" style="color: var(--ink);">Build Diary</span>
            </a>

            <a class="mono text-xs uppercase tracking-widest transition-opacity hover:opacity-60" style="color: var(--silver);" href="{{ route('public.gallery', $project->user) }}">
                {{ __('app.public.view_more_projects') }}
            </a>

            <div class="flex items-center gap-3">
                <a class="mono flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-widest transition-opacity hover:opacity-70" style="background: var(--ink); color: var(--paper);" href="{{ route('public.project.pdf', $project->slug) }}">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('app.public.download_pdf') }}
                </a>
                <a class="mono flex items-center gap-2 border px-4 py-2 text-xs font-medium uppercase tracking-widest transition-opacity hover:opacity-60" style="border-color: var(--mist); color: var(--silver);" href="{{ route('public.project.zip', $project->slug) }}">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('app.public.download_zip') }}
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-8 py-16">
        <article>
            <!-- Project Header -->
            <header class="mb-16">
                <!-- Annotation row -->
                <div class="mb-8 flex items-center gap-4">
                    <hr class="flex-1 hairline divider">
                    <div class="mono text-xs uppercase tracking-widest" style="color: var(--silver);">
                        @if ($project->category) {{ $project->category->name }} · @endif PROYECTO
                    </div>
                    <hr class="w-12 hairline divider">
                </div>

                <!-- Title -->
                <h1 class="serif mb-6 text-6xl font-semibold leading-none tracking-tight sm:text-7xl" style="color: var(--ink);">
                    {{ $project->title }}
                </h1>

                <!-- Status and meta -->
                <div class="mb-8 flex flex-wrap items-center gap-6">
                    @if ($project->status)
                        <span class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $project->status->color }};"></span>
                            <span class="mono text-xs uppercase tracking-widest" style="color: var(--graphite);">{{ $project->status->name }}</span>
                        </span>
                    @endif
                    @if ($project->priority && $project->priority > 0)
                        @php
                            $priorityLabel = match ($project->priority) {
                                1 => 'Prioridad baja',
                                2 => 'Prioridad media',
                                3 => 'Prioridad alta',
                                default => '',
                            };
                            $priorityColor = match ($project->priority) {
                                1 => '#22c55e',
                                2 => '#f59e0b',
                                3 => 'var(--accent-mark)',
                                default => '#8a8a8a',
                            };
                        @endphp
                        <span class="mono text-xs uppercase tracking-widest" style="color: {{ $priorityColor }};">{{ $priorityLabel }}</span>
                    @endif
                </div>

                <!-- Tags -->
                @if ($project->tags->count() > 0)
                    <div class="mb-8 flex flex-wrap gap-2">
                        @foreach ($project->tags as $tag)
                            <span class="tag-arq" style="border-color: {{ $tag->color }}60; color: {{ $tag->color }}; background: {{ $tag->color }}08;">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Dates as dimension lines -->
                @if ($project->started_at || $project->due_date || $project->completed_at)
                    <div class="flex flex-wrap gap-8">
                        @if ($project->started_at)
                            <div>
                                <p class="mono mb-1 text-xs uppercase tracking-widest" style="color: var(--mist);">Inicio</p>
                                <p class="serif text-xl" style="color: var(--graphite);">{{ $project->started_at->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if ($project->due_date)
                            <div>
                                <p class="mono mb-1 text-xs uppercase tracking-widest" style="color: var(--mist);">Plazo</p>
                                <p class="serif text-xl" style="color: var(--graphite);">{{ $project->due_date->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if ($project->completed_at)
                            <div>
                                <p class="mono mb-1 text-xs uppercase tracking-widest" style="color: var(--mist);">Completado</p>
                                <p class="serif text-xl" style="color: var(--ink); font-weight: 600;">{{ $project->completed_at->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="mt-8 flex items-center gap-5 border-l-2 py-3 pl-5" style="border-color: var(--ink);">
                        <div class="serif flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-xl font-semibold" style="background: var(--paper-dark); border: 1px solid var(--rule); color: var(--ink);">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="mono mb-1 text-xs uppercase tracking-widest" style="color: var(--silver);">
                                @if ($project->person_reason) {{ $project->person_reason_label }} · @endif Dedicado a
                            </p>
                            <p class="serif text-xl" style="color: var(--ink);">{{ $project->person->name }}</p>
                        </div>
                    </div>
                @endif
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-14">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Galería</h2>
                        <span class="section-label">§ 01</span>
                    </div>
                    <hr class="divider-heavy mb-6">
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($project->files->where('type', 'image') as $file)
                            <figure class="group relative overflow-hidden" style="border: 1px solid var(--rule);">
                                <a href="{{ $file->url }}" target="_blank">
                                    <img class="h-56 w-full object-cover transition-all duration-500 group-hover:scale-105" src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                </a>
                                @if ($file->description)
                                    <figcaption class="mono absolute inset-x-0 bottom-0 translate-y-full p-3 text-xs uppercase tracking-wider transition-transform duration-300 group-hover:translate-y-0" style="background: rgba(247,246,243,0.95); color: var(--graphite);">
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
                <section class="mb-14">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Descripción</h2>
                        <span class="section-label">§ 02</span>
                    </div>
                    <hr class="divider-heavy mb-8">
                    <div class="prose-arq prose prose-sm max-w-none leading-relaxed" style="color: var(--graphite);">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </section>
            @endif

            <!-- Checklist -->
            @if ($project->tasks->count() > 0)
                <section class="mb-14">
                    @php
                        $completedCount = $project->tasks->where('is_completed', true)->count();
                        $totalCount = $project->tasks->count();
                        $progress = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                    @endphp
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Tareas</h2>
                        <div class="flex items-center gap-4">
                            <span class="mono text-xs" style="color: var(--silver);">{{ $completedCount }}/{{ $totalCount }}</span>
                            <span class="section-label">§ 03</span>
                        </div>
                    </div>
                    <hr class="divider-heavy mb-6">

                    <!-- Progress bar -->
                    <div class="mb-2 h-px w-full" style="background: var(--rule);">
                        <div class="progress-bar h-full transition-all duration-700" style="width: {{ $progress }}%;"></div>
                    </div>
                    <div class="mono mb-8 flex justify-between text-xs" style="color: var(--mist);">
                        <span>0%</span>
                        <span class="mark-red">{{ $progress }}%</span>
                        <span>100%</span>
                    </div>

                    <ul class="divide-y" style="border-top: 1px solid var(--rule); border-bottom: 1px solid var(--rule);">
                        @foreach ($project->tasks as $task)
                            <li class="flex items-start gap-4 py-3">
                                <span class="mono mt-0.5 shrink-0 text-sm" style="color: {{ $task->is_completed ? 'var(--ink)' : 'var(--mist)' }}; width: 1.25rem;">
                                    {{ $task->is_completed ? '×' : '○' }}
                                </span>
                                <div class="flex-1">
                                    <span class="text-sm" style="color: {{ $task->is_completed ? 'var(--silver)' : 'var(--graphite)' }}; {{ $task->is_completed ? 'text-decoration: line-through;' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                    @if ($task->description)
                                        <p class="mono mt-0.5 text-xs" style="color: var(--mist);">{{ $task->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Budget -->
            @if ($project->expenses->count() > 0)
                <section class="mb-14">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Presupuesto</h2>
                        <span class="section-label">§ 04</span>
                    </div>
                    <hr class="divider-heavy mb-6">

                    <!-- Summary -->
                    <div class="mb-8 grid grid-cols-3 divide-x" style="border: 1px solid var(--rule); divide-color: var(--rule);">
                        <div class="grid-bg p-5">
                            <p class="mono mb-2 text-xs uppercase tracking-widest" style="color: var(--silver);">Total estimado</p>
                            <p class="serif text-3xl font-semibold" style="color: var(--ink);">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="p-5" style="background: var(--ink);">
                            <p class="mono mb-2 text-xs uppercase tracking-widest" style="color: var(--silver);">Ejecutado</p>
                            <p class="serif text-3xl font-semibold" style="color: var(--paper);">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="grid-bg p-5">
                            <p class="mono mb-2 text-xs uppercase tracking-widest" style="color: var(--silver);">Pendiente</p>
                            <p class="serif text-3xl" style="color: var(--graphite);">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
                        </div>
                    </div>

                    @php
                        $categories = [
                            'material' => ['name' => __('app.public.expense_categories.material')],
                            'tool' => ['name' => __('app.public.expense_categories.tool')],
                            'consumable' => ['name' => __('app.public.expense_categories.consumable')],
                            'service' => ['name' => __('app.public.expense_categories.service')],
                            'other' => ['name' => __('app.public.expense_categories.other')],
                        ];
                        $groupedExpenses = $project->expenses->groupBy('category');
                    @endphp

                    <div class="divide-y" style="border: 1px solid var(--rule);">
                        @foreach ($groupedExpenses as $category => $expenses)
                            @php
                                $categoryInfo = $categories[$category] ?? ['name' => ucfirst($category)];
                                $categoryTotal = $expenses->sum('total_price');
                            @endphp
                            <details class="group">
                                <summary class="flex cursor-pointer list-none items-center justify-between px-5 py-4 transition-colors hover:bg-paper-dark" style="background: var(--paper-dark);">
                                    <span class="flex items-center gap-3">
                                        <span class="mono text-xs uppercase tracking-widest" style="color: var(--graphite);">{{ $categoryInfo['name'] }}</span>
                                        <span class="mono text-xs" style="color: var(--silver);">({{ $expenses->count() }})</span>
                                    </span>
                                    <span class="serif text-lg font-semibold" style="color: var(--ink);">{{ number_format($categoryTotal, 2, ',', '.') }} €</span>
                                </summary>
                                <div style="border-top: 1px solid var(--rule);">
                                    <ul class="divide-y" style="divide-color: var(--rule);">
                                        @foreach ($expenses as $expense)
                                            <li class="flex items-center justify-between px-5 py-3 text-sm">
                                                <div class="flex items-center gap-3">
                                                    <span class="mono text-xs" style="color: {{ $expense->is_purchased ? 'var(--ink)' : 'var(--mist)' }}; width: 1rem; text-align: center;">
                                                        {{ $expense->is_purchased ? '×' : '○' }}
                                                    </span>
                                                    <span style="color: {{ $expense->is_purchased ? 'var(--silver)' : 'var(--graphite)' }}; {{ $expense->is_purchased ? 'text-decoration: line-through;' : '' }}">
                                                        {{ $expense->name }}
                                                        @if ($expense->supplier)
                                                            <span style="color: var(--mist);"> · {{ $expense->supplier }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-5">
                                                    <span class="mono text-xs" style="color: var(--silver);">{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €</span>
                                                    <span class="serif font-semibold" style="color: var(--ink); min-width: 5rem; text-align: right;">{{ number_format($expense->total_price, 2, ',', '.') }} €</span>
                                                    @if ($expense->url)
                                                        <a href="{{ $expense->url }}" target="_blank" rel="noopener" class="mono text-xs" style="color: var(--silver);">↗</a>
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
                <section class="mb-14">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Referencias</h2>
                        <span class="section-label">§ 05</span>
                    </div>
                    <hr class="divider-heavy mb-6">
                    <ul class="divide-y" style="border-top: 1px solid var(--rule); border-bottom: 1px solid var(--rule);">
                        @foreach ($project->links as $link)
                            <li class="flex items-start gap-4 py-4">
                                <span class="mono mt-1 shrink-0 text-xs" style="color: var(--mist);">→</span>
                                <div>
                                    <a class="serif text-lg font-semibold transition-opacity hover:opacity-60" style="color: var(--ink);" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $link->title }}
                                    </a>
                                    @if ($link->description)
                                        <p class="mt-0.5 text-sm" style="color: var(--silver);">{{ $link->description }}</p>
                                    @endif
                                    <p class="mono mt-1 text-xs" style="color: var(--mist);">{{ $link->url }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Downloadable Files -->
            @if ($project->files->where('type', '!=', 'image')->count() > 0)
                <section class="mb-14">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Archivos</h2>
                        <span class="section-label">§ 06</span>
                    </div>
                    <hr class="divider-heavy mb-6">
                    <ul class="divide-y" style="border-top: 1px solid var(--rule); border-bottom: 1px solid var(--rule);">
                        @foreach ($project->files->where('type', '!=', 'image') as $file)
                            <li class="flex items-center justify-between py-4 text-sm">
                                <div class="flex items-center gap-4">
                                    <span class="mono text-xs uppercase" style="color: var(--silver);">
                                        {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                                    </span>
                                    <div>
                                        <a class="serif text-lg font-semibold transition-opacity hover:opacity-60" style="color: var(--ink);" href="{{ $file->url }}" target="_blank" download>
                                            {{ $file->name }}
                                        </a>
                                        <p class="mono text-xs" style="color: var(--mist);">{{ number_format($file->size / 1024, 0) }} KB</p>
                                    </div>
                                </div>
                                <a class="mono text-sm transition-opacity hover:opacity-60" style="color: var(--ink);" href="{{ $file->url }}" title="{{ __('app.public.download') }}" download>↓</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Diary / Build Log -->
            @if ($project->diaryEntries->count() > 0)
                <section class="mt-16">
                    <div class="mb-6 flex items-end justify-between">
                        <h2 class="serif text-3xl font-semibold" style="color: var(--ink);">Diario</h2>
                        <span class="section-label">§ 07</span>
                    </div>
                    <hr class="divider-heavy mb-12">

                    <!-- Timeline -->
                    <div class="relative">
                        <!-- Vertical rule -->
                        <div class="absolute bottom-0 left-0 top-0 w-px" style="background: var(--rule); margin-left: 5.5rem;"></div>

                        <div class="space-y-10">
                            @foreach ($project->diaryEntries as $entry)
                                <div class="relative flex gap-8">
                                    <!-- Date column -->
                                    <div class="w-20 shrink-0 pt-0.5 text-right">
                                        <time class="serif block text-xl font-semibold leading-tight" style="color: var(--ink);">{{ $entry->entry_date->format('d') }}</time>
                                        <span class="mono block text-xs uppercase tracking-widest" style="color: var(--silver);">{{ $entry->entry_date->format('M Y') }}</span>
                                    </div>

                                    <!-- Node on the line -->
                                    <div class="absolute left-0 top-1.5 h-3 w-3 rounded-full" style="margin-left: 4.75rem; background: var(--ink); border: 2px solid var(--paper);"></div>

                                    <!-- Content -->
                                    <article class="flex-1 pb-2">
                                        <!-- Type badge -->
                                        @if ($entry->type)
                                            @php
                                                $typeConfig = match ($entry->type) {
                                                    'progress' => ['label' => 'Avance'],
                                                    'issue' => ['label' => 'Incidencia'],
                                                    'solution' => ['label' => 'Solución'],
                                                    'milestone' => ['label' => 'Hito'],
                                                    'note' => ['label' => 'Nota'],
                                                    default => ['label' => ucfirst($entry->type)],
                                                };
                                            @endphp
                                            <span class="mono mb-2 inline-block text-xs uppercase tracking-widest" style="color: var(--silver);">{{ $typeConfig['label'] }}@if ($entry->time_spent_minutes) · {{ floor($entry->time_spent_minutes / 60) }}h{{ $entry->time_spent_minutes % 60 > 0 ? $entry->time_spent_minutes % 60 . 'm' : '' }}@endif</span>
                                        @elseif ($entry->time_spent_minutes)
                                            <span class="mono mb-2 inline-block text-xs uppercase tracking-widest" style="color: var(--silver);">{{ floor($entry->time_spent_minutes / 60) }}h{{ $entry->time_spent_minutes % 60 > 0 ? $entry->time_spent_minutes % 60 . 'm' : '' }}</span>
                                        @endif

                                        @if ($entry->title)
                                            <h3 class="serif mb-3 text-2xl font-semibold" style="color: var(--ink);">{{ $entry->title }}</h3>
                                        @endif

                                        <div class="prose-arq prose prose-sm max-w-none" style="color: var(--graphite);">
                                            {!! $entry->content !!}
                                        </div>

                                        @if ($entry->images->count() > 0)
                                            <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                                @foreach ($entry->images as $image)
                                                    <a href="{{ $image->url }}" target="_blank" class="group block overflow-hidden" style="border: 1px solid var(--rule);">
                                                        <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy" class="h-28 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer -->
    <footer class="mt-20 border-t py-10" style="border-color: var(--rule); background: var(--paper);">
        <div class="mx-auto max-w-5xl px-8">
            <hr class="divider-heavy mb-6">
            <div class="flex items-center justify-between">
                <span class="mono text-xs uppercase tracking-widest" style="color: var(--silver);">{{ __('app.public.published_with') }}</span>
                <span class="mono text-xs" style="color: var(--mist);">Build Diary</span>
            </div>
        </div>
    </footer>
</body>

</html>

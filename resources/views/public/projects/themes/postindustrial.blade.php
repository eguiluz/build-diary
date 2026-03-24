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
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400&family=barlow:400,500,600,700&family=barlow-condensed:400,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --rust: #c0522a;
            --rust-light: #e07348;
            --rust-dim: #7a3318;
            --iron: #3a3a3c;
            --steel: #52525b;
            --concrete: #27272a;
            --concrete-light: #3f3f46;
            --smoke: #a1a1aa;
            --ash: #d4d4d8;
            --bg: #18181b;
            --surface: #1f1f22;
            --surface-2: #27272a;
            --text: #e4e4e7;
            --text-muted: #71717a;
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Barlow', ui-sans-serif, system-ui, sans-serif;
        }
        .condensed { font-family: 'Barlow Condensed', ui-sans-serif, system-ui, sans-serif; }
        .display { font-family: 'Bebas Neue', 'Barlow Condensed', ui-sans-serif, system-ui, sans-serif; letter-spacing: 0.04em; }
        .rust { color: var(--rust-light); }
        .text-muted-pi { color: var(--text-muted); }

        /* Diagonal stripe texture overlay */
        .stripe-bg {
            background-image: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 4px,
                rgba(255,255,255,0.015) 4px,
                rgba(255,255,255,0.015) 8px
            );
        }
        .rust-gradient { background: linear-gradient(135deg, var(--rust-dim) 0%, var(--rust) 50%, var(--rust-light) 100%); }
        .progress-bar { background: linear-gradient(90deg, var(--rust-dim) 0%, var(--rust-light) 100%); }

        .prose-pi h1, .prose-pi h2, .prose-pi h3 { color: var(--ash); font-family: 'Barlow Condensed', sans-serif; font-weight: 700; letter-spacing: 0.02em; }
        .prose-pi a { color: var(--rust-light); }
        .prose-pi code { background: var(--surface-2); color: var(--rust-light); padding: 0.1em 0.3em; border-radius: 2px; font-size: 0.85em; }
        .prose-pi pre { background: var(--surface-2) !important; border-left: 3px solid var(--rust); }
        .prose-pi blockquote { border-left: 3px solid var(--rust); color: var(--smoke); font-style: italic; }
        .prose-pi strong { color: var(--ash); }
        .prose-pi p { line-height: 1.75; }

        /* Rivet decorators */
        .rivet::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--rust);
            border: 1px solid var(--rust-light);
            margin-right: 8px;
            vertical-align: middle;
            flex-shrink: 0;
        }

        /* Section headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--iron);
        }
        .section-header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: repeating-linear-gradient(90deg, var(--iron) 0px, var(--iron) 6px, transparent 6px, transparent 12px);
        }

        /* Warning stripe top border for cards */
        .hazard-top {
            border-top: 4px solid;
            border-image: repeating-linear-gradient(90deg, var(--rust) 0px, var(--rust) 12px, #18181b 12px, #18181b 20px) 1;
        }
    </style>
</head>

<body class="min-h-screen antialiased">

    <!-- Top bar -->
    <header style="background: var(--concrete); border-bottom: 3px solid var(--rust);">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <a class="condensed flex items-center gap-3 text-sm font-semibold uppercase tracking-widest transition-opacity hover:opacity-70" href="{{ url('/') }}" style="color: var(--rust-light);">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Build Diary
            </a>

            <a class="condensed text-sm font-medium uppercase tracking-wider transition-opacity hover:opacity-70" style="color: var(--text-muted);" href="{{ route('public.gallery', $project->user) }}">
                {{ __('app.public.view_more_projects') }}
            </a>

            <div class="flex items-center gap-3">
                <a class="condensed flex items-center gap-2 px-4 py-2 text-sm font-bold uppercase tracking-wider transition-opacity hover:opacity-80" style="background: var(--rust); color: #fff;" href="{{ route('public.project.pdf', $project->slug) }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('app.public.download_pdf') }}
                </a>
                <a class="condensed flex items-center gap-2 border px-4 py-2 text-sm font-medium uppercase tracking-wider transition-opacity hover:opacity-80" style="border-color: var(--steel); color: var(--text-muted);" href="{{ route('public.project.zip', $project->slug) }}">
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
            <header class="mb-14">
                <!-- Plate header -->
                <div class="stripe-bg mb-8 rounded-sm p-6" style="background-color: var(--surface); border: 1px solid var(--iron);">
                    <!-- Meta strip -->
                    <div class="condensed mb-5 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs font-semibold uppercase tracking-widest" style="color: var(--text-muted);">
                        <span class="flex items-center gap-1.5" style="color: var(--rust-light);">
                            ◈ PROYECTO
                        </span>
                        @if ($project->status)
                            <span class="flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full" style="background-color: {{ $project->status->color }};"></span>
                                {{ strtoupper($project->status->name) }}
                            </span>
                        @endif
                        @if ($project->category)
                            <span>/ {{ strtoupper($project->category->name) }}</span>
                        @endif
                        @if ($project->priority && $project->priority > 0)
                            @php
                                $priorityLabel = match ($project->priority) {
                                    1 => 'PRIORIDAD BAJA',
                                    2 => 'PRIORIDAD MEDIA',
                                    3 => 'PRIORIDAD ALTA',
                                    default => '',
                                };
                                $priorityColor = match ($project->priority) {
                                    1 => '#22c55e',
                                    2 => '#f59e0b',
                                    3 => '#ef4444',
                                    default => '#71717a',
                                };
                            @endphp
                            <span style="color: {{ $priorityColor }};">⚠ {{ $priorityLabel }}</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="display mb-5 text-5xl leading-none sm:text-6xl" style="color: var(--ash);">
                        {{ strtoupper($project->title) }}
                    </h1>

                    <!-- Tags -->
                    @if ($project->tags->count() > 0)
                        <div class="mb-5 flex flex-wrap gap-2">
                            @foreach ($project->tags as $tag)
                                <span class="condensed rounded-sm px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider" style="background-color: {{ $tag->color }}18; border: 1px solid {{ $tag->color }}50; color: {{ $tag->color }};">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Dates -->
                    <div class="condensed flex flex-wrap gap-6 text-sm" style="color: var(--text-muted);">
                        @if ($project->started_at)
                            <span class="flex items-center gap-1.5">
                                <span style="color: var(--rust-light);">▶</span>
                                INICIO: <span style="color: var(--ash);">{{ $project->started_at->format('d.m.Y') }}</span>
                            </span>
                        @endif
                        @if ($project->due_date)
                            <span class="flex items-center gap-1.5">
                                <span style="color: var(--rust-light);">◼</span>
                                PLAZO: <span style="color: var(--ash);">{{ $project->due_date->format('d.m.Y') }}</span>
                            </span>
                        @endif
                        @if ($project->completed_at)
                            <span class="flex items-center gap-1.5">
                                <span style="color: #4ade80;">✓</span>
                                COMPLETADO: <span style="color: #4ade80;">{{ $project->completed_at->format('d.m.Y') }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="flex items-center gap-4 rounded-sm p-4" style="background: var(--concrete); border-left: 4px solid var(--rust);">
                        <div class="condensed flex h-11 w-11 shrink-0 items-center justify-center rounded-sm text-lg font-bold" style="background: var(--rust); color: #fff;">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="condensed text-xs font-semibold uppercase tracking-widest" style="color: var(--text-muted);">
                                @if ($project->person_reason)
                                    {{ strtoupper($project->person_reason_label) }}
                                @else
                                    DEDICADO A
                                @endif
                            </p>
                            <p class="font-semibold" style="color: var(--ash);">{{ $project->person->name }}</p>
                        </div>
                    </div>
                @endif
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-12">
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">GALERÍA</span>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($project->files->where('type', 'image') as $file)
                            <figure class="group relative overflow-hidden rounded-sm" style="border: 1px solid var(--iron);">
                                <a href="{{ $file->url }}" target="_blank">
                                    <img class="h-56 w-full object-cover opacity-75 grayscale-[20%] transition-all duration-300 group-hover:opacity-100 group-hover:grayscale-0" src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                    <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-15" style="background: var(--rust);"></div>
                                </a>
                                @if ($file->description)
                                    <figcaption class="condensed absolute inset-x-0 bottom-0 p-2 text-xs font-medium uppercase tracking-wider opacity-0 transition-opacity group-hover:opacity-100" style="background: rgba(24,24,27,0.9); color: var(--rust-light);">
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
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">DESCRIPCIÓN</span>
                    </div>
                    <div class="prose-pi prose prose-invert prose-sm max-w-none leading-relaxed" style="color: var(--text);">
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
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">TAREAS</span>
                        <span class="condensed text-sm font-semibold" style="color: var(--text-muted);">{{ $completedCount }}/{{ $totalCount }} · {{ $progress }}%</span>
                    </div>

                    <!-- Progress bar -->
                    <div class="mb-6 h-2 w-full rounded-sm" style="background: var(--concrete);">
                        <div class="progress-bar h-full rounded-sm transition-all duration-700" style="width: {{ $progress }}%;"></div>
                    </div>

                    <ul class="space-y-2">
                        @foreach ($project->tasks as $task)
                            <li class="flex items-start gap-3 rounded-sm px-3 py-2 text-sm" style="background: var(--surface); border-left: 3px solid {{ $task->is_completed ? 'var(--rust)' : 'var(--iron)' }};">
                                <span class="condensed mt-0.5 shrink-0 text-xs font-bold" style="color: {{ $task->is_completed ? 'var(--rust-light)' : 'var(--steel)' }};">
                                    {{ $task->is_completed ? '■' : '□' }}
                                </span>
                                <div>
                                    <span style="color: {{ $task->is_completed ? 'var(--text-muted)' : 'var(--ash)' }}; {{ $task->is_completed ? 'text-decoration: line-through;' : '' }}">
                                        {{ $task->title }}
                                    </span>
                                    @if ($task->description)
                                        <p class="mt-0.5 text-xs" style="color: var(--text-muted);">{{ $task->description }}</p>
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
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">PRESUPUESTO</span>
                    </div>

                    <!-- Summary -->
                    <div class="mb-6 grid grid-cols-3 gap-3">
                        <div class="stripe-bg rounded-sm p-4" style="border: 1px solid var(--iron);">
                            <p class="condensed mb-1 text-xs font-semibold uppercase tracking-widest" style="color: var(--text-muted);">TOTAL</p>
                            <p class="display text-2xl" style="color: var(--ash);">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="stripe-bg rounded-sm p-4" style="border: 1px solid var(--rust-dim);">
                            <p class="condensed mb-1 text-xs font-semibold uppercase tracking-widest" style="color: var(--rust-light);">GASTADO</p>
                            <p class="display text-2xl" style="color: var(--rust-light);">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="stripe-bg rounded-sm p-4" style="border: 1px solid var(--iron);">
                            <p class="condensed mb-1 text-xs font-semibold uppercase tracking-widest" style="color: var(--text-muted);">PENDIENTE</p>
                            <p class="display text-2xl" style="color: var(--text-muted);">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
                        </div>
                    </div>

                    @php
                        $categories = [
                            'material' => ['name' => __('app.public.expense_categories.material'), 'icon' => '◼'],
                            'tool' => ['name' => __('app.public.expense_categories.tool'), 'icon' => '⚙'],
                            'consumable' => ['name' => __('app.public.expense_categories.consumable'), 'icon' => '◈'],
                            'service' => ['name' => __('app.public.expense_categories.service'), 'icon' => '◉'],
                            'other' => ['name' => __('app.public.expense_categories.other'), 'icon' => '◇'],
                        ];
                        $groupedExpenses = $project->expenses->groupBy('category');
                    @endphp

                    <div class="space-y-3">
                        @foreach ($groupedExpenses as $category => $expenses)
                            @php
                                $categoryInfo = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '◇'];
                                $categoryTotal = $expenses->sum('total_price');
                            @endphp
                            <details class="group rounded-sm" style="border: 1px solid var(--iron);">
                                <summary class="condensed flex cursor-pointer list-none items-center justify-between px-4 py-3 text-sm font-semibold uppercase tracking-wider" style="color: var(--text-muted); background: var(--surface);">
                                    <span class="flex items-center gap-2">
                                        <span style="color: var(--rust-light);">{{ $categoryInfo['icon'] }}</span>
                                        {{ strtoupper($categoryInfo['name']) }}
                                        <span class="rounded-sm px-1.5 py-0.5 text-xs font-bold" style="background: var(--surface-2); color: var(--rust-light);">{{ $expenses->count() }}</span>
                                    </span>
                                    <span style="color: var(--ash);">{{ number_format($categoryTotal, 2, ',', '.') }} €</span>
                                </summary>
                                <div style="border-top: 1px solid var(--iron);">
                                    <ul class="divide-y" style="--tw-divide-opacity: 1;">
                                        @foreach ($expenses as $expense)
                                            <li class="flex items-center justify-between px-4 py-2.5 text-sm" style="border-color: var(--iron);">
                                                <div class="flex items-center gap-3">
                                                    <span class="shrink-0 text-xs" style="color: {{ $expense->is_purchased ? 'var(--rust-light)' : 'var(--steel)' }};">
                                                        {{ $expense->is_purchased ? '■' : '□' }}
                                                    </span>
                                                    <span style="color: {{ $expense->is_purchased ? 'var(--text-muted)' : 'var(--ash)' }}; {{ $expense->is_purchased ? 'text-decoration: line-through;' : '' }}">
                                                        {{ $expense->name }}
                                                        @if ($expense->supplier)
                                                            <span style="color: var(--text-muted);"> — {{ $expense->supplier }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-xs" style="color: var(--text-muted);">{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €</span>
                                                    <span class="font-semibold" style="color: {{ $expense->is_purchased ? 'var(--rust-light)' : 'var(--ash)' }}; min-width: 5rem; text-align: right;">{{ number_format($expense->total_price, 2, ',', '.') }} €</span>
                                                    @if ($expense->url)
                                                        <a href="{{ $expense->url }}" target="_blank" rel="noopener" style="color: var(--rust-light);">↗</a>
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
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">REFERENCIAS</span>
                    </div>
                    <ul class="space-y-3">
                        @foreach ($project->links as $link)
                            <li class="flex items-start gap-3 rounded-sm px-4 py-3" style="background: var(--surface); border-left: 3px solid var(--rust);">
                                <span style="color: var(--rust-light); flex-shrink: 0; margin-top: 1px;">→</span>
                                <div>
                                    <a class="condensed font-semibold uppercase tracking-wide transition-opacity hover:opacity-70" style="color: var(--rust-light);" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $link->title }}
                                    </a>
                                    @if ($link->description)
                                        <p class="mt-0.5 text-sm" style="color: var(--text-muted);">{{ $link->description }}</p>
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
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">ARCHIVOS</span>
                    </div>
                    <ul class="space-y-2">
                        @foreach ($project->files->where('type', '!=', 'image') as $file)
                            <li class="flex items-center justify-between rounded-sm px-4 py-3 text-sm" style="background: var(--surface); border: 1px solid var(--iron);">
                                <div class="flex items-center gap-3">
                                    @if ($file->type === 'stl')
                                        <span style="color: #60a5fa;">◈</span>
                                    @elseif ($file->type === 'pdf')
                                        <span style="color: var(--rust-light);">◈</span>
                                    @else
                                        <span style="color: var(--steel);">◈</span>
                                    @endif
                                    <div>
                                        <a class="condensed font-semibold uppercase tracking-wide transition-opacity hover:opacity-70" style="color: var(--ash);" href="{{ $file->url }}" target="_blank" download>
                                            {{ $file->name }}
                                        </a>
                                        <span class="ml-2 text-xs" style="color: var(--text-muted);">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                        </span>
                                    </div>
                                </div>
                                <a class="transition-opacity hover:opacity-70" style="color: var(--rust-light);" href="{{ $file->url }}" title="{{ __('app.public.download') }}" download>↓</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Diary / Build Log -->
            @if ($project->diaryEntries->count() > 0)
                <section class="mt-16">
                    <div class="section-header">
                        <span class="display text-2xl tracking-widest" style="color: var(--rust-light);">DIARIO DE OBRA</span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($project->diaryEntries as $entry)
                            <article class="rounded-sm" style="border: 1px solid var(--iron); background: var(--surface);">
                                <!-- Entry header bar -->
                                <div class="stripe-bg flex flex-wrap items-center gap-x-6 gap-y-2 px-4 py-3" style="border-bottom: 1px solid var(--iron); background: var(--concrete);">
                                    <time class="display text-lg leading-none" style="color: var(--rust-light);">
                                        {{ $entry->entry_date->format('d.m.Y') }}
                                    </time>
                                    @if ($entry->type)
                                        @php
                                            $typeConfig = match ($entry->type) {
                                                'progress' => ['label' => 'AVANCE', 'color' => '#60a5fa'],
                                                'issue' => ['label' => 'INCIDENCIA', 'color' => '#f87171'],
                                                'solution' => ['label' => 'SOLUCIÓN', 'color' => '#4ade80'],
                                                'milestone' => ['label' => 'HITO', 'color' => '#c084fc'],
                                                'note' => ['label' => 'NOTA', 'color' => '#a1a1aa'],
                                                default => ['label' => strtoupper($entry->type), 'color' => '#a1a1aa'],
                                            };
                                        @endphp
                                        <span class="condensed rounded-sm px-2 py-0.5 text-xs font-bold uppercase tracking-widest" style="background: {{ $typeConfig['color'] }}20; color: {{ $typeConfig['color'] }}; border: 1px solid {{ $typeConfig['color'] }}40;">
                                            {{ $typeConfig['label'] }}
                                        </span>
                                    @endif
                                    @if ($entry->time_spent_minutes)
                                        <span class="condensed text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                            ⏱ {{ floor($entry->time_spent_minutes / 60) }}h{{ $entry->time_spent_minutes % 60 > 0 ? $entry->time_spent_minutes % 60 . 'm' : '' }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Entry content -->
                                <div class="p-5">
                                    @if ($entry->title)
                                        <h3 class="display mb-3 text-xl" style="color: var(--ash);">{{ strtoupper($entry->title) }}</h3>
                                    @endif

                                    <div class="prose-pi prose prose-invert prose-sm max-w-none" style="color: var(--text);">
                                        {!! $entry->content !!}
                                    </div>

                                    @if ($entry->images->count() > 0)
                                        <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                            @foreach ($entry->images as $image)
                                                <a href="{{ $image->url }}" target="_blank" class="group block overflow-hidden rounded-sm" style="border: 1px solid var(--iron);">
                                                    <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy" class="h-28 w-full object-cover opacity-70 grayscale-[20%] transition-all duration-300 group-hover:opacity-100 group-hover:grayscale-0">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer -->
    <footer class="condensed mt-20 border-t py-8 text-center text-xs font-semibold uppercase tracking-widest" style="border-color: var(--iron); color: var(--text-muted); background: var(--concrete);">
        <span style="color: var(--rust-light);">◼ </span>{{ __('app.public.published_with') }}
    </footer>
</body>

</html>

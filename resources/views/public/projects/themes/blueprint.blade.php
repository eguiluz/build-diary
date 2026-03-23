<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $project->title }} - {{ config('app.name', 'Build Diary') }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($project->description), 160) }}">

    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($project->description), 160) }}">
    <meta property="og:type" content="article">

    @include('partials.favicon')

    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=rajdhani:400,500,600,700&family=jetbrains-mono:400,500,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg:        #060d1b;
            --surface:   #0b1629;
            --surface-2: #0f1e38;
            --border:    rgba(34, 211, 238, 0.18);
            --border-bright: rgba(34, 211, 238, 0.45);
            --cyan:      #22d3ee;
            --cyan-dim:  #0891b2;
            --cyan-glow: rgba(34, 211, 238, 0.08);
            --text:      #e0f2fe;
            --text-muted:#64a5bf;
            --grid-color: rgba(34, 211, 238, 0.05);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg);
            background-image:
                linear-gradient(var(--grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
            background-size: 40px 40px;
            color: var(--text);
            font-family: 'Rajdhani', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        .mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            position: relative;
        }

        /* Corner brackets decoration */
        .card::before, .card::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-color: var(--cyan);
            border-style: solid;
        }
        .card::before {
            top: -1px; left: -1px;
            border-width: 2px 0 0 2px;
        }
        .card::after {
            bottom: -1px; right: -1px;
            border-width: 0 2px 2px 0;
        }

        .section-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--cyan);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .btn-primary {
            background: transparent;
            border: 1px solid var(--cyan);
            color: var(--cyan);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            padding: 0.4rem 1rem;
            transition: background 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary:hover {
            background: var(--cyan-glow);
            box-shadow: 0 0 12px rgba(34,211,238,0.2);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--border-bright);
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            padding: 0.4rem 1rem;
            transition: border-color 0.2s, color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-secondary:hover { border-color: var(--cyan); color: var(--cyan); }

        .progress-track {
            height: 4px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--cyan-dim), var(--cyan));
            box-shadow: 0 0 8px rgba(34,211,238,0.5);
            transition: width 0.7s ease;
        }

        .badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            padding: 0.2rem 0.6rem;
            border: 1px solid;
            border-radius: 2px;
        }

        .timeline-line {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, var(--cyan) 0%, var(--cyan-dim) 60%, transparent 100%);
        }

        .timeline-node {
            width: 10px;
            height: 10px;
            border: 1px solid var(--cyan);
            background: var(--bg);
            position: absolute;
            left: -5px;
            top: 1.25rem;
            transform: rotate(45deg);
        }
        .timeline-node::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--cyan);
        }

        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }

        .prose-bp { color: var(--text); line-height: 1.75; }
        .prose-bp h1, .prose-bp h2, .prose-bp h3 { color: var(--cyan); font-family: 'Rajdhani', sans-serif; font-weight: 700; }
        .prose-bp a { color: var(--cyan); text-decoration: underline; }
        .prose-bp strong { color: #fff; }
        .prose-bp code { font-family: 'JetBrains Mono', monospace; background: var(--surface-2); color: var(--cyan); padding: 0.1em 0.4em; border: 1px solid var(--border); font-size: 0.85em; }
        .prose-bp blockquote { border-left: 2px solid var(--cyan); padding-left: 1rem; color: var(--text-muted); }
        .prose-bp ul { list-style: none; padding: 0; }
        .prose-bp ul li::before { content: '▸ '; color: var(--cyan); }
    </style>
</head>

<body>
    <!-- Header -->
    <header style="border-bottom: 1px solid var(--border); background: rgba(6,13,27,0.95); backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 50;">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3">
            <a href="{{ url('/') }}" class="mono flex items-center gap-2 text-sm transition-opacity hover:opacity-70" style="color: var(--cyan);">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                BUILD_DIARY
            </a>

            <a href="{{ route('public.gallery', $project->user) }}" class="mono text-xs transition-colors hover:text-white" style="color: var(--text-muted);">
                ← {{ __('app.public.view_more_projects') }}
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.project.pdf', $project->slug) }}" class="btn-primary">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('app.public.download_pdf') }}
                </a>
                <a href="{{ route('public.project.zip', $project->slug) }}" class="btn-secondary">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('app.public.download_zip') }}
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-12">

        <!-- Project Header -->
        <header class="mb-12">
            <!-- Status row -->
            <div class="mono mb-5 flex flex-wrap items-center gap-4 text-xs" style="color: var(--text-muted);">
                @if ($project->status)
                    <span class="badge" style="border-color: {{ $project->status->color }}; color: {{ $project->status->color }};">
                        {{ strtoupper($project->status->name) }}
                    </span>
                @endif
                @if ($project->category)
                    <span class="badge" style="border-color: var(--border-bright); color: var(--text-muted);">
                        {{ strtoupper($project->category->name) }}
                    </span>
                @endif
                @if ($project->priority && $project->priority > 0)
                    @php
                        $priorityColor = match($project->priority) { 1=>'#4ade80', 2=>'#fbbf24', 3=>'#f87171', default=>'#64748b' };
                        $priorityLabel = match($project->priority) { 1=>__('app.public.priority_low'), 2=>__('app.public.priority_medium'), 3=>__('app.public.priority_high'), default=>'' };
                    @endphp
                    <span class="badge" style="border-color: {{ $priorityColor }}50; color: {{ $priorityColor }};">{{ strtoupper($priorityLabel) }}</span>
                @endif
            </div>

            <!-- Title -->
            <h1 class="mb-5 text-5xl font-bold leading-tight tracking-wide sm:text-6xl" style="color: #fff; text-shadow: 0 0 40px rgba(34,211,238,0.15);">
                {{ $project->title }}
            </h1>

            <!-- Tags -->
            @if ($project->tags->count() > 0)
                <div class="mb-6 flex flex-wrap gap-2">
                    @foreach ($project->tags as $tag)
                        <span class="mono badge text-xs" style="border-color: {{ $tag->color }}40; color: {{ $tag->color }};">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Dates & Person -->
            <div class="mono flex flex-wrap gap-6 text-xs" style="color: var(--text-muted);">
                @if ($project->started_at)
                    <span>INIT · <span style="color: var(--cyan);">{{ $project->started_at->format('Y-m-d') }}</span></span>
                @endif
                @if ($project->due_date)
                    <span>DUE · <span style="color: var(--text);">{{ $project->due_date->format('Y-m-d') }}</span></span>
                @endif
                @if ($project->completed_at)
                    <span>COMPLETED · <span style="color: #4ade80;">{{ $project->completed_at->format('Y-m-d') }}</span></span>
                @endif
            </div>

            @if ($project->person)
                <div class="mono mt-6 flex items-center gap-3" style="color: var(--text-muted); font-size: 0.75rem;">
                    <span style="color: var(--cyan);">◈</span>
                    @if ($project->person_reason)
                        {{ strtoupper($project->person_reason_label) }} FOR
                    @else
                        DEDICATED TO
                    @endif
                    <span style="color: var(--text);">{{ strtoupper($project->person->name) }}</span>
                </div>
            @endif
        </header>

        <!-- Image Gallery -->
        @if ($project->files->where('type', 'image')->count() > 0)
            <section class="mb-12">
                <div class="section-label">Gallery · {{ $project->files->where('type', 'image')->count() }} files</div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($project->files->where('type', 'image') as $file)
                        <figure class="group relative overflow-hidden card">
                            <a href="{{ $file->url }}" target="_blank" class="block">
                                <img src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}"
                                    loading="lazy"
                                    class="h-56 w-full object-cover transition-all duration-300 group-hover:scale-105"
                                    style="filter: saturate(0.7) brightness(0.85); transition: filter 0.3s, transform 0.3s;"
                                    onmouseover="this.style.filter='saturate(1) brightness(1)'"
                                    onmouseout="this.style.filter='saturate(0.7) brightness(0.85)'">
                                <!-- Scan line overlay -->
                                <div class="pointer-events-none absolute inset-0 opacity-20"
                                    style="background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(34,211,238,0.03) 2px, rgba(34,211,238,0.03) 4px);"></div>
                            </a>
                            @if ($file->description)
                                <figcaption class="mono absolute inset-x-0 bottom-0 p-3 text-xs opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                    style="background: linear-gradient(to top, rgba(6,13,27,0.95), transparent); color: var(--cyan);">
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
                <div class="section-label">Description</div>
                <div class="card p-6">
                    <div class="prose-bp prose max-w-none">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </div>
            </section>
        @endif

        <!-- Two-column layout for Checklist + Budget -->
        @if ($project->tasks->count() > 0 || $project->expenses->count() > 0)
            <div class="mb-12 grid gap-6 lg:grid-cols-2">
                <!-- Checklist -->
                @if ($project->tasks->count() > 0)
                    @php
                        $done = $project->tasks->where('is_completed', true)->count();
                        $total = $project->tasks->count();
                        $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                    @endphp
                    <section class="card p-6">
                        <div class="section-label">Checklist</div>
                        <div class="mono mb-1 flex items-end justify-between text-xs" style="color: var(--text-muted);">
                            <span>{{ $done }}/{{ $total }} TASKS</span>
                            <span style="color: var(--cyan);">{{ $pct }}%</span>
                        </div>
                        <div class="progress-track mb-6">
                            <div class="progress-fill" style="width: {{ $pct }}%;"></div>
                        </div>
                        <ul class="mono space-y-2.5 text-sm">
                            @foreach ($project->tasks as $task)
                                <li class="flex items-start gap-3">
                                    <span class="shrink-0 mt-0.5" style="color: {{ $task->is_completed ? '#22d3ee' : 'rgba(34,211,238,0.25)' }};">
                                        {{ $task->is_completed ? '◆' : '◇' }}
                                    </span>
                                    <div>
                                        <span style="color: {{ $task->is_completed ? 'var(--text-muted)' : 'var(--text)' }}; {{ $task->is_completed ? 'text-decoration: line-through;' : '' }}">
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

                <!-- Budget summary -->
                @if ($project->expenses->count() > 0)
                    <section class="card p-6">
                        <div class="section-label">Budget</div>
                        <div class="mb-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="mono text-xs" style="color: var(--text-muted);">TOTAL</span>
                                <span class="mono text-xl font-bold" style="color: var(--text);">{{ number_format($project->total_budget, 2, ',', '.') }} €</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="mono text-xs" style="color: var(--cyan);">SPENT</span>
                                <span class="mono text-xl font-bold" style="color: var(--cyan);">{{ number_format($project->spent_budget, 2, ',', '.') }} €</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="mono text-xs" style="color: var(--text-muted);">PENDING</span>
                                <span class="mono text-xl font-bold" style="color: var(--text-muted);">{{ number_format($project->pending_budget, 2, ',', '.') }} €</span>
                            </div>
                        </div>
                        @php $bp = $project->budget_progress; @endphp
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $bp }}%;"></div>
                        </div>
                        <p class="mono mt-2 text-right text-xs" style="color: var(--text-muted);">{{ $bp }}% {{ __('app.public.budget_percent_spent', ['percent' => '']) }}</p>
                    </section>
                @endif
            </div>
        @endif

        <!-- Expenses detail -->
        @if ($project->expenses->count() > 0)
            <section class="mb-12">
                <div class="section-label">Bill of Materials</div>
                @php
                    $categories = [
                        'material' => ['name' => __('app.public.expense_categories.material'), 'icon' => '🧱'],
                        'tool'     => ['name' => __('app.public.expense_categories.tool'),     'icon' => '🔧'],
                        'consumable'=>['name' => __('app.public.expense_categories.consumable'),'icon' => '📦'],
                        'service'  => ['name' => __('app.public.expense_categories.service'),  'icon' => '👷'],
                        'other'    => ['name' => __('app.public.expense_categories.other'),    'icon' => '📋'],
                    ];
                    $groupedExpenses = $project->expenses->groupBy('category');
                @endphp
                <div class="space-y-3">
                    @foreach ($groupedExpenses as $category => $expenses)
                        @php
                            $info = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '📦'];
                            $catTotal = $expenses->sum('total_price');
                        @endphp
                        <details class="card group">
                            <summary class="mono flex cursor-pointer items-center justify-between p-4 text-xs" style="color: var(--text-muted);">
                                <span class="flex items-center gap-3">
                                    <span style="color: var(--cyan);">▶</span>
                                    <span class="tracking-widest">{{ strtoupper($info['name']) }}</span>
                                    <span class="badge" style="border-color: var(--border); padding: 0.1rem 0.4rem;">{{ $expenses->count() }}</span>
                                </span>
                                <span style="color: var(--text);">{{ number_format($catTotal, 2, ',', '.') }} €</span>
                            </summary>
                            <div style="border-top: 1px solid var(--border);">
                                <table class="mono w-full text-xs" style="color: var(--text-muted);">
                                    <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr style="border-bottom: 1px solid var(--border);">
                                                <td class="py-2.5 pl-4 pr-2" style="color: {{ $expense->is_purchased ? '#22d3ee' : 'rgba(34,211,238,0.2)' }}; width: 1rem;">
                                                    {{ $expense->is_purchased ? '◆' : '◇' }}
                                                </td>
                                                <td class="py-2.5 pr-4" style="color: {{ $expense->is_purchased ? 'var(--text-muted)' : 'var(--text)' }}; {{ $expense->is_purchased ? 'text-decoration: line-through;' : '' }}">
                                                    {{ $expense->name }}
                                                    @if ($expense->supplier)
                                                        <span style="color: var(--text-muted);"> · {{ $expense->supplier }}</span>
                                                    @endif
                                                </td>
                                                <td class="py-2.5 pr-4 text-right" style="color: var(--text-muted);">
                                                    {{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €
                                                </td>
                                                <td class="py-2.5 pr-4 text-right font-bold" style="color: {{ $expense->is_purchased ? 'var(--cyan)' : 'var(--text)' }}; white-space: nowrap;">
                                                    {{ number_format($expense->total_price, 2, ',', '.') }} €
                                                </td>
                                                <td class="py-2.5 pr-3 text-center">
                                                    @if ($expense->url)
                                                        <a href="{{ $expense->url }}" target="_blank" rel="noopener" style="color: var(--cyan);" class="hover:opacity-70">↗</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Links + Files row -->
        @if ($project->links->count() > 0 || $project->files->where('type', '!=', 'image')->count() > 0)
            <div class="mb-12 grid gap-6 lg:grid-cols-2">
                @if ($project->links->count() > 0)
                    <section class="card p-6">
                        <div class="section-label">References</div>
                        <ul class="mono space-y-3 text-sm">
                            @foreach ($project->links as $link)
                                <li class="flex items-start gap-3">
                                    <span style="color: var(--cyan); flex-shrink: 0;">▸</span>
                                    <div>
                                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                            class="font-medium transition-opacity hover:opacity-70" style="color: var(--cyan);">
                                            {{ $link->title }}
                                        </a>
                                        @if ($link->description)
                                            <p class="mt-0.5 text-xs" style="color: var(--text-muted);">{{ $link->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($project->files->where('type', '!=', 'image')->count() > 0)
                    <section class="card p-6">
                        <div class="section-label">Files</div>
                        <ul class="mono space-y-2 text-sm">
                            @foreach ($project->files->where('type', '!=', 'image') as $file)
                                <li class="flex items-center justify-between py-1">
                                    <div class="flex items-center gap-2">
                                        <span style="color: {{ $file->type === 'stl' ? 'var(--cyan)' : ($file->type === 'pdf' ? '#f87171' : 'var(--text-muted)') }};">◈</span>
                                        <div>
                                            <a href="{{ $file->url }}" target="_blank" download
                                                class="transition-opacity hover:opacity-70" style="color: var(--cyan);">
                                                {{ $file->name }}
                                            </a>
                                            <span class="ml-2 text-xs" style="color: var(--text-muted);">
                                                {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ $file->url }}" download title="{{ __('app.public.download') }}"
                                        class="transition-opacity hover:opacity-70" style="color: var(--cyan);">↓</a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        @endif

        <!-- Build Log / Diary -->
        @if ($project->diaryEntries->count() > 0)
            <section class="mt-16">
                <div class="section-label">Build Log · {{ $project->diaryEntries->count() }} entries</div>

                <div class="relative pl-8">
                    <div class="timeline-line"></div>

                    <div class="space-y-6">
                        @foreach ($project->diaryEntries as $entry)
                            <article class="relative card p-6">
                                <div class="timeline-node"></div>

                                <!-- Header -->
                                <div class="mono mb-4 flex flex-wrap items-center gap-4 text-xs">
                                    <time style="color: var(--cyan); font-weight: 600;">{{ $entry->entry_date->format('Y-m-d') }}</time>
                                    @if ($entry->type)
                                        @php
                                            [$typeLabel, $typeColor] = match($entry->type) {
                                                'progress'  => [__('app.public.entry_types.progress'),  '#60a5fa'],
                                                'issue'     => [__('app.public.entry_types.issue'),     '#f87171'],
                                                'solution'  => [__('app.public.entry_types.solution'),  '#4ade80'],
                                                'milestone' => [__('app.public.entry_types.milestone'), 'var(--cyan)'],
                                                'note'      => [__('app.public.entry_types.note'),      '#a1a1aa'],
                                                default     => [ucfirst($entry->type), '#a1a1aa'],
                                            };
                                        @endphp
                                        <span class="badge" style="border-color: {{ $typeColor }}50; color: {{ $typeColor }};">
                                            {{ strtoupper($typeLabel) }}
                                        </span>
                                    @endif
                                    @if ($entry->time_spent_minutes)
                                        <span style="color: var(--text-muted);">
                                            ⏱ {{ floor($entry->time_spent_minutes / 60) }}h {{ $entry->time_spent_minutes % 60 }}m
                                        </span>
                                    @endif
                                </div>

                                @if ($entry->title)
                                    <h3 class="mb-3 text-lg font-bold tracking-wide" style="color: #fff;">{{ $entry->title }}</h3>
                                @endif

                                <div class="prose-bp prose prose-sm max-w-none">
                                    {!! $entry->content !!}
                                </div>

                                @if ($entry->images->count() > 0)
                                    <div class="mt-5 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                        @foreach ($entry->images as $image)
                                            <a href="{{ $image->url }}" target="_blank" class="group/img block overflow-hidden" style="border: 1px solid var(--border);">
                                                <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy"
                                                    class="h-28 w-full object-cover"
                                                    style="filter: saturate(0.6) brightness(0.8); transition: filter 0.3s;"
                                                    onmouseover="this.style.filter='saturate(1) brightness(1)'"
                                                    onmouseout="this.style.filter='saturate(0.6) brightness(0.8)'">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="mono mt-20 py-8 text-center text-xs" style="border-top: 1px solid var(--border); color: var(--text-muted);">
        <span style="color: var(--cyan);">◆</span>
        &nbsp;{{ __('app.public.published_with') }}&nbsp;
        <span style="color: var(--cyan);">◆</span>
    </footer>
</body>

</html>

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
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,400i,600,700,700i&family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --cream:     #faf5ec;
            --cream-2:   #f3ead8;
            --cream-3:   #e8dcc8;
            --brown-dark:#2c1a0e;
            --brown:     #6b3f22;
            --brown-mid: #8b5e3c;
            --gold:      #c4933f;
            --gold-light:#d4a96a;
            --gold-pale: #f0dcb0;
            --ink:       #2c1a0e;
            --ink-mid:   #5c3d28;
            --ink-light: #9b7557;
            --border:    #d6c4a8;
            --border-light: #e8dcc8;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--cream);
            /* Subtle wood grain via noise-like gradient */
            background-image:
                repeating-linear-gradient(
                    92deg,
                    transparent 0px,
                    rgba(139,94,60,0.015) 1px,
                    transparent 2px,
                    transparent 60px
                ),
                repeating-linear-gradient(
                    88deg,
                    transparent 0px,
                    rgba(196,147,63,0.01) 1px,
                    transparent 2px,
                    transparent 90px
                );
            color: var(--ink);
            font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
            min-height: 100vh;
        }

        .sans { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }

        .ornament { color: var(--gold); }

        /* Divider with ornament */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border), transparent);
        }
        .divider-text {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
        }

        /* Card style */
        .card {
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(44,26,14,0.06), 0 1px 3px rgba(44,26,14,0.04);
        }

        /* Inset card */
        .card-inset {
            background: var(--cream-2);
            border: 1px solid var(--border-light);
        }

        /* Badge */
        .badge {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 0.25rem 0.75rem;
            border: 1px solid var(--border);
            color: var(--ink-mid);
            background: var(--cream);
        }

        /* Progress */
        .progress-track {
            height: 6px;
            background: var(--cream-3);
            border: 1px solid var(--border);
            border-radius: 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--brown-mid), var(--gold));
            transition: width 0.7s ease;
        }

        /* Prose */
        .prose-wood { color: var(--ink); line-height: 1.85; }
        .prose-wood h1, .prose-wood h2, .prose-wood h3 {
            font-family: 'Playfair Display', serif;
            color: var(--brown-dark);
            font-weight: 700;
        }
        .prose-wood a { color: var(--brown-mid); text-decoration: underline; text-underline-offset: 3px; }
        .prose-wood strong { color: var(--brown-dark); }
        .prose-wood em { color: var(--ink-mid); }
        .prose-wood blockquote {
            border-left: 3px solid var(--gold);
            padding-left: 1.25rem;
            color: var(--ink-mid);
            font-style: italic;
        }
        .prose-wood code {
            font-size: 0.85em;
            background: var(--cream-2);
            border: 1px solid var(--border-light);
            padding: 0.1em 0.35em;
            color: var(--brown-mid);
        }

        /* Timeline */
        .timeline-dot {
            width: 12px;
            height: 12px;
            border: 2px solid var(--gold);
            background: var(--cream);
            border-radius: 50%;
            position: absolute;
            left: -6px;
            top: 1.5rem;
        }
        .timeline-dot::after {
            content: '';
            position: absolute;
            inset: 2px;
            border-radius: 50%;
            background: var(--gold);
        }

        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>

<body>
    <!-- Header -->
    <header style="background: var(--brown-dark); border-bottom: 3px solid var(--gold);">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-8 py-4">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 transition-opacity hover:opacity-80">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="sans text-base font-semibold tracking-widest" style="color: var(--gold-light); letter-spacing: 0.2em;">BUILD DIARY</span>
            </a>

            <a href="{{ route('public.gallery', $project->user) }}"
                class="sans text-xs tracking-widest transition-opacity hover:opacity-70"
                style="color: var(--gold-pale); letter-spacing: 0.15em;">
                ← {{ strtoupper(__('app.public.view_more_projects')) }}
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.project.pdf', $project->slug) }}"
                    class="sans flex items-center gap-2 px-4 py-2 text-xs font-semibold tracking-widest transition-opacity hover:opacity-80"
                    style="background: var(--gold); color: var(--brown-dark); letter-spacing: 0.1em;">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ strtoupper(__('app.public.download_pdf')) }}
                </a>
                <a href="{{ route('public.project.zip', $project->slug) }}"
                    class="sans flex items-center gap-2 border px-4 py-2 text-xs tracking-widest transition-opacity hover:opacity-80"
                    style="border-color: var(--gold-light); color: var(--gold-light); letter-spacing: 0.1em;">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ strtoupper(__('app.public.download_zip')) }}
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-8 py-16">
        <article>
            <!-- Project Header -->
            <header class="mb-16 text-center">
                <!-- Decorative top ornament -->
                <p class="mb-6 text-3xl" style="color: var(--gold); letter-spacing: 0.5em;">✦ ✦ ✦</p>

                <!-- Meta badges -->
                <div class="sans mb-8 flex flex-wrap items-center justify-center gap-3">
                    @if ($project->status)
                        <span class="badge" style="border-color: {{ $project->status->color }}60; color: {{ $project->status->color }};">
                            {{ $project->status->name }}
                        </span>
                    @endif
                    @if ($project->category)
                        <span class="badge">{{ $project->category->name }}</span>
                    @endif
                    @if ($project->priority && $project->priority > 0)
                        @php
                            $priorityColor = match($project->priority) { 1=>'#4a7c59', 2=>'#c4933f', 3=>'#8b2318', default=>'var(--ink-light)' };
                            $priorityLabel = match($project->priority) { 1=>__('app.public.priority_low'), 2=>__('app.public.priority_medium'), 3=>__('app.public.priority_high'), default=>'' };
                        @endphp
                        <span class="badge" style="border-color: {{ $priorityColor }}50; color: {{ $priorityColor }};">{{ $priorityLabel }}</span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="mb-6 text-5xl font-bold leading-tight sm:text-6xl" style="color: var(--brown-dark); font-style: italic;">
                    {{ $project->title }}
                </h1>

                <!-- Tags -->
                @if ($project->tags->count() > 0)
                    <div class="sans mb-8 flex flex-wrap justify-center gap-2">
                        @foreach ($project->tags as $tag)
                            <span class="text-xs tracking-widest" style="color: {{ $tag->color }}; letter-spacing: 0.15em;">
                                ◆ {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Dates -->
                <div class="sans flex flex-wrap items-center justify-center gap-6 text-sm" style="color: var(--ink-light);">
                    @if ($project->started_at)
                        <span>{{ __('app.public.started') }}: <strong style="color: var(--ink-mid);">{{ $project->started_at->format('d M Y') }}</strong></span>
                    @endif
                    @if ($project->due_date)
                        <span style="color: var(--border);">|</span>
                        <span>{{ __('app.public.due_date') }}: <strong style="color: var(--ink-mid);">{{ $project->due_date->format('d M Y') }}</strong></span>
                    @endif
                    @if ($project->completed_at)
                        <span style="color: var(--border);">|</span>
                        <span>{{ __('app.public.completed') }}: <strong style="color: var(--brown-mid);">{{ $project->completed_at->format('d M Y') }}</strong></span>
                    @endif
                </div>

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="mt-10 flex items-center justify-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center text-lg font-bold"
                            style="background: var(--brown-dark); color: var(--gold);">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="sans text-xs uppercase tracking-widest" style="color: var(--ink-light);">
                                @if ($project->person_reason) {{ $project->person_reason_label }} {{ __('app.public.for') }}
                                @else {{ __('app.public.dedicated_to') }} @endif
                            </p>
                            <p class="text-xl font-semibold" style="color: var(--brown-dark);">{{ $project->person->name }}</p>
                        </div>
                    </div>
                @endif

                <!-- Bottom ornament -->
                <p class="mt-10 text-xl" style="color: var(--gold-pale); letter-spacing: 1em;">— ✦ —</p>
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.image_gallery') }}</span></div>
                    @php $images = $project->files->where('type', 'image'); @endphp
                    @if ($images->count() === 1)
                        <figure style="border: 6px solid #fff; outline: 1px solid var(--border); box-shadow: 0 8px 32px rgba(44,26,14,0.15);">
                            <a href="{{ $images->first()->url }}" target="_blank">
                                <img class="w-full object-cover" style="max-height: 520px;" src="{{ $images->first()->url }}"
                                    alt="{{ $images->first()->description ?? $images->first()->original_name }}" loading="lazy">
                            </a>
                            @if ($images->first()->description)
                                <figcaption class="sans bg-white p-3 text-center text-sm italic" style="color: var(--ink-light);">
                                    {{ $images->first()->description }}
                                </figcaption>
                            @endif
                        </figure>
                    @else
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($images as $file)
                                <figure class="group" style="border: 4px solid #fff; outline: 1px solid var(--border); box-shadow: 0 4px 16px rgba(44,26,14,0.1);">
                                    <a href="{{ $file->url }}" target="_blank" class="block overflow-hidden">
                                        <img class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                    </a>
                                    @if ($file->description)
                                        <figcaption class="sans bg-white p-2 text-center text-xs italic" style="color: var(--ink-light);">{{ $file->description }}</figcaption>
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
                    <div class="divider"><span class="divider-text">{{ __('app.public.description') }}</span></div>
                    <div class="prose-wood prose max-w-none text-lg leading-relaxed">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </section>
            @endif

            <!-- Checklist -->
            @if ($project->tasks->count() > 0)
                <section class="mb-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.checklist') }}</span></div>
                    @php
                        $done = $project->tasks->where('is_completed', true)->count();
                        $total = $project->tasks->count();
                        $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                    @endphp
                    <div class="card p-8">
                        <div class="sans mb-3 flex justify-between text-sm" style="color: var(--ink-light);">
                            <span>{{ $done }} {{ __('app.public.tasks') }} {{ __('app.public.all_completed') !== __('app.public.all_completed') ? '' : '' }} de {{ $total }}</span>
                            <span style="color: var(--gold); font-weight: 600;">{{ $pct }}%</span>
                        </div>
                        <div class="progress-track mb-8">
                            <div class="progress-fill" style="width: {{ $pct }}%;"></div>
                        </div>
                        <ul class="space-y-4">
                            @foreach ($project->tasks as $task)
                                <li class="flex items-start gap-4">
                                    <span class="mt-1 shrink-0 text-lg" style="color: {{ $task->is_completed ? 'var(--gold)' : 'var(--border)' }}; line-height: 1;">
                                        {{ $task->is_completed ? '✦' : '◇' }}
                                    </span>
                                    <div>
                                        <p class="sans {{ $task->is_completed ? 'line-through' : '' }}" style="color: {{ $task->is_completed ? 'var(--ink-light)' : 'var(--ink)' }};">
                                            {{ $task->title }}
                                        </p>
                                        @if ($task->description)
                                            <p class="sans mt-0.5 text-sm italic" style="color: var(--ink-light);">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            <!-- Budget -->
            @if ($project->expenses->count() > 0)
                <section class="mb-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.budget') }}</span></div>

                    <!-- Summary -->
                    <div class="mb-8 grid grid-cols-3 gap-4">
                        <div class="card-inset p-5 text-center">
                            <p class="sans mb-1 text-xs uppercase tracking-widest" style="color: var(--ink-light);">{{ __('app.public.total') }}</p>
                            <p class="text-2xl font-bold" style="color: var(--brown-dark);">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="card-inset p-5 text-center" style="border-color: var(--gold-pale);">
                            <p class="sans mb-1 text-xs uppercase tracking-widest" style="color: var(--gold);">{{ __('app.public.spent') }}</p>
                            <p class="text-2xl font-bold" style="color: var(--brown-mid);">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                        </div>
                        <div class="card-inset p-5 text-center">
                            <p class="sans mb-1 text-xs uppercase tracking-widest" style="color: var(--ink-light);">{{ __('app.public.pending') }}</p>
                            <p class="text-2xl font-bold" style="color: var(--ink-light);">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
                        </div>
                    </div>

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
                    <div class="card">
                        @foreach ($groupedExpenses as $category => $expenses)
                            @php
                                $info = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '📦'];
                                $catTotal = $expenses->sum('total_price');
                            @endphp
                            <details class="group" style="border-bottom: 1px solid var(--border-light);">
                                <summary class="sans flex cursor-pointer items-center justify-between p-5 text-sm" style="color: var(--ink-mid);">
                                    <span class="flex items-center gap-3 font-semibold uppercase tracking-widest text-xs">
                                        <span style="color: var(--gold);">{{ $info['icon'] }}</span>
                                        {{ $info['name'] }}
                                        <span class="rounded-full px-2 py-0.5 text-xs" style="background: var(--cream-3); color: var(--ink-light);">{{ $expenses->count() }}</span>
                                    </span>
                                    <span class="font-semibold" style="color: var(--brown-mid);">{{ number_format($catTotal, 2, ',', '.') }} €</span>
                                </summary>
                                <div style="border-top: 1px solid var(--border-light); background: var(--cream-2); padding: 0.5rem 0;">
                                    @foreach ($expenses as $expense)
                                        <div class="sans flex items-center justify-between px-6 py-2.5 text-sm">
                                            <div class="flex items-center gap-3">
                                                <span style="color: {{ $expense->is_purchased ? 'var(--gold)' : 'var(--border)' }};">
                                                    {{ $expense->is_purchased ? '✦' : '◇' }}
                                                </span>
                                                <span style="color: {{ $expense->is_purchased ? 'var(--ink-light)' : 'var(--ink)' }}; {{ $expense->is_purchased ? 'text-decoration: line-through;' : '' }}">
                                                    {{ $expense->name }}
                                                    @if ($expense->supplier)
                                                        <span style="color: var(--ink-light); font-style: italic;"> · {{ $expense->supplier }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-4" style="color: var(--ink-light);">
                                                <span class="text-xs">{{ $expense->quantity }}{{ $expense->unit ? ' '.$expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €</span>
                                                <span class="font-semibold" style="color: {{ $expense->is_purchased ? 'var(--brown-mid)' : 'var(--ink)' }}; min-width: 5rem; text-align: right;">
                                                    {{ number_format($expense->total_price, 2, ',', '.') }} €
                                                </span>
                                                @if ($expense->url)
                                                    <a href="{{ $expense->url }}" target="_blank" rel="noopener" style="color: var(--gold);" class="hover:opacity-70">↗</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Links -->
            @if ($project->links->count() > 0)
                <section class="mb-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.links') }}</span></div>
                    <ul class="sans space-y-4">
                        @foreach ($project->links as $link)
                            <li class="flex items-start gap-4">
                                <span class="mt-1 text-lg" style="color: var(--gold);">✦</span>
                                <div>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                        class="font-semibold transition-opacity hover:opacity-70" style="color: var(--brown-mid);">
                                        {{ $link->title }}
                                    </a>
                                    @if ($link->description)
                                        <p class="mt-0.5 text-sm italic" style="color: var(--ink-light);">{{ $link->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <!-- Downloadable Files -->
            @if ($project->files->where('type', '!=', 'image')->count() > 0)
                <section class="mb-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.downloadable_files') }}</span></div>
                    <div class="card">
                        @foreach ($project->files->where('type', '!=', 'image') as $file)
                            <div class="sans flex items-center justify-between border-b px-6 py-4 last:border-b-0" style="border-color: var(--border-light);">
                                <div class="flex items-center gap-3">
                                    <span style="color: var(--gold);">
                                        @if ($file->type === 'stl') ◈
                                        @elseif ($file->type === 'pdf') ▣
                                        @else ◇ @endif
                                    </span>
                                    <div>
                                        <a href="{{ $file->url }}" target="_blank" download
                                            class="font-medium transition-opacity hover:opacity-70" style="color: var(--brown-mid);">
                                            {{ $file->name }}
                                        </a>
                                        <span class="ml-2 text-xs" style="color: var(--ink-light);">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ $file->url }}" download title="{{ __('app.public.download') }}"
                                    class="px-4 py-1.5 text-xs font-semibold tracking-widest transition-opacity hover:opacity-80"
                                    style="background: var(--cream-2); border: 1px solid var(--border); color: var(--brown-mid);">
                                    ↓ {{ strtoupper(__('app.public.download')) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Diary -->
            @if ($project->diaryEntries->count() > 0)
                <section class="mt-16">
                    <div class="divider"><span class="divider-text">{{ __('app.public.project_diary') }}</span></div>

                    <div class="relative pl-10">
                        <!-- Timeline line -->
                        <div class="absolute bottom-4 left-0 top-4 w-px"
                            style="background: linear-gradient(to bottom, var(--gold), var(--gold-pale));"></div>

                        <div class="space-y-10">
                            @foreach ($project->diaryEntries as $entry)
                                <article class="relative">
                                    <div class="timeline-dot"></div>

                                    <div class="card p-8">
                                        <!-- Header -->
                                        <div class="sans mb-5 flex flex-wrap items-center gap-4">
                                            <time class="text-sm font-semibold" style="color: var(--gold);">
                                                {{ $entry->entry_date->format('d \d\e F \d\e Y') }}
                                            </time>
                                            @if ($entry->type)
                                                @php
                                                    $typeLabel = match($entry->type) {
                                                        'progress' => __('app.public.entry_types.progress'),
                                                        'issue'    => __('app.public.entry_types.issue'),
                                                        'solution' => __('app.public.entry_types.solution'),
                                                        'milestone'=> __('app.public.entry_types.milestone'),
                                                        'note'     => __('app.public.entry_types.note'),
                                                        default    => ucfirst($entry->type),
                                                    };
                                                @endphp
                                                <span class="badge">{{ $typeLabel }}</span>
                                            @endif
                                            @if ($entry->time_spent_minutes)
                                                <span class="text-sm italic" style="color: var(--ink-light);">
                                                    {{ floor($entry->time_spent_minutes / 60) }}h {{ $entry->time_spent_minutes % 60 }}m
                                                </span>
                                            @endif
                                        </div>

                                        @if ($entry->title)
                                            <h3 class="mb-4 text-2xl font-bold italic" style="color: var(--brown-dark);">{{ $entry->title }}</h3>
                                        @endif

                                        <div class="prose-wood prose max-w-none">
                                            {!! $entry->content !!}
                                        </div>

                                        @if ($entry->images->count() > 0)
                                            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                                @foreach ($entry->images as $image)
                                                    <a href="{{ $image->url }}" target="_blank"
                                                        class="group block overflow-hidden"
                                                        style="border: 3px solid #fff; outline: 1px solid var(--border); box-shadow: 0 2px 8px rgba(44,26,14,0.1);">
                                                        <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy"
                                                            class="h-32 w-full object-cover transition-transform duration-500 group-hover:scale-105">
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
    <footer style="background: var(--brown-dark); border-top: 3px solid var(--gold); margin-top: 6rem;">
        <div class="mx-auto max-w-5xl px-8 py-8 text-center">
            <p class="text-xl mb-2" style="color: var(--gold); letter-spacing: 0.5em;">✦ ✦ ✦</p>
            <p class="sans text-sm" style="color: var(--gold-pale);">{{ __('app.public.published_with') }}</p>
        </div>
    </footer>
</body>

</html>

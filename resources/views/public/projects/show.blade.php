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
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-linear-to-br min-h-screen from-slate-50 to-slate-100 font-sans antialiased dark:from-slate-900 dark:to-slate-800">
    <!-- Navigation -->
    <header class="w-full border-b border-slate-200 dark:border-slate-800">
        <nav class="mx-auto max-w-5xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-2 transition-opacity hover:opacity-80" href="{{ url('/') }}">
                    <svg class="h-7 w-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">Build Diary</span>
                </a>

                <a class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white" href="{{ route('public.gallery', $project->user) }}">
                    {{ __('app.public.view_more_projects') }}
                </a>

                <div class="flex items-center gap-3">
                    <a class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-amber-600 hover:shadow-md" href="{{ route('public.project.pdf', $project->slug) }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('app.public.download_pdf') }}
                    </a>
                    <a class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700" href="{{ route('public.project.zip', $project->slug) }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ __('app.public.download_zip') }}
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Project Header -->
        <article>
            <header class="mb-12">
                <!-- Status & Category -->
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    @if ($project->status)
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }}">
                            {{ $project->status->name }}
                        </span>
                    @endif
                    @if ($project->category)
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" style="background-color: {{ $project->category->color ?? '#64748b' }}20; color: {{ $project->category->color ?? '#64748b' }}">
                            {{ $project->category->name }}
                        </span>
                    @endif
                    @if ($project->priority && $project->priority > 0)
                        @php
                            $priorityColor = match ($project->priority) {
                                1 => '#22c55e', // green - baja
                                2 => '#f59e0b', // amber - media
                                3 => '#ef4444', // red - alta
                                default => '#64748b',
                            };
                            $priorityLabel = match ($project->priority) {
                                1 => __('app.public.priority_low'),
                                2 => __('app.public.priority_medium'),
                                3 => __('app.public.priority_high'),
                                default => '',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium" style="background-color: {{ $priorityColor }}20; color: {{ $priorityColor }}">
                            @if ($project->priority === 3)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            @elseif ($project->priority === 1)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                </svg>
                            @endif
                            {{ $priorityLabel }}
                        </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="mb-4 text-4xl font-bold text-slate-900 sm:text-5xl dark:text-white">
                    {{ $project->title }}
                </h1>

                <!-- Tags -->
                @if ($project->tags->count() > 0)
                    <div class="mb-6 flex flex-wrap gap-2">
                        @foreach ($project->tags as $tag)
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-slate-500 dark:text-slate-400">
                    @if ($project->started_at)
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ __('app.public.started') }}: {{ $project->started_at->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($project->due_date)
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('app.public.due_date') }}: {{ $project->due_date->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($project->completed_at)
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('app.public.completed') }}: {{ $project->completed_at->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Dedicated to -->
                @if ($project->person)
                    <div class="mt-6 flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                        <div class="bg-linear-to-br flex h-12 w-12 shrink-0 items-center justify-center rounded-full from-amber-400 to-orange-500 text-lg font-bold text-white shadow-md">
                            {{ strtoupper(substr($project->person->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                @if ($project->person_reason)
                                    {{ $project->person_reason_label }} {{ __('app.public.for') }}
                                @else
                                    {{ __('app.public.dedicated_to') }}
                                @endif
                            </p>
                            <p class="font-semibold text-slate-900 dark:text-white">
                                {{ $project->person->name }}
                            </p>
                        </div>
                    </div>
                @endif
            </header>

            <!-- Image Gallery -->
            @if ($project->files->where('type', 'image')->count() > 0)
                <section class="mb-12">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($project->files->where('type', 'image') as $file)
                            <figure class="group relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                                <a class="block" href="{{ $file->url }}" target="_blank">
                                    <img class="h-64 w-full object-cover transition-transform duration-300 group-hover:scale-105" src="{{ $file->url }}" alt="{{ $file->description ?? $file->original_name }}" loading="lazy">
                                    @if ($file->description)
                                        <figcaption class="bg-linear-to-t absolute inset-x-0 bottom-0 from-black/70 to-transparent p-4 text-sm text-white opacity-0 transition-opacity group-hover:opacity-100">
                                            {{ $file->description }}
                                        </figcaption>
                                    @endif
                                </a>
                            </figure>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Description -->
            @if ($project->description)
                <section class="mb-12">
                    <div class="prose prose-slate dark:prose-invert prose-headings:font-semibold prose-a:text-amber-600 dark:prose-a:text-amber-400 max-w-none">
                        {!! Str::markdown($project->description, ['renderer' => ['soft_break' => "<br>\n"]]) !!}
                    </div>
                </section>
            @endif

            <!-- Checklist Progress -->
            @if ($project->tasks->count() > 0)
                <section class="mb-12">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                                <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                {{ __('app.public.checklist') }}
                            </h2>
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                {{ $project->tasks->where('is_completed', true)->count() }} / {{ $project->tasks->count() }} {{ __('app.public.tasks') }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $progress = $project->tasks->count() > 0 ? round(($project->tasks->where('is_completed', true)->count() / $project->tasks->count()) * 100) : 0;
                        @endphp
                        <div class="mb-6">
                            <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                                <div class="{{ $progress === 100 ? 'bg-green-500' : 'bg-amber-500' }} h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-sm">
                                <span class="{{ $progress === 100 ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }} font-medium">
                                    {{ __('app.public.percent_completed', ['percent' => $progress]) }}
                                </span>
                                @if ($progress === 100)
                                    <span class="flex items-center gap-1 text-green-600 dark:text-green-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ __('app.public.all_completed') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Task List -->
                        <ul class="space-y-3">
                            @foreach ($project->tasks as $task)
                                <li class="{{ $task->is_completed ? 'opacity-60' : '' }} flex items-start gap-3">
                                    <div class="mt-0.5 shrink-0">
                                        @if ($task->is_completed)
                                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <span class="{{ $task->is_completed ? 'line-through text-slate-500 dark:text-slate-400' : 'text-slate-900 dark:text-white' }}">
                                            {{ $task->title }}
                                        </span>
                                        @if ($task->description)
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            <!-- Budget / Expenses -->
            @if ($project->expenses->count() > 0)
                <section class="mb-12">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                                <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('app.public.budget') }}
                            </h2>
                        </div>

                        <!-- Budget Summary -->
                        <div class="mb-6 grid grid-cols-3 gap-4">
                            <div class="rounded-xl bg-slate-50 p-4 text-center dark:bg-slate-700/50">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('app.public.total') }}</p>
                                <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($project->total_budget, 2, ',', '.') }} €</p>
                            </div>
                            <div class="rounded-xl bg-green-50 p-4 text-center dark:bg-green-900/20">
                                <p class="text-xs font-medium uppercase tracking-wide text-green-600 dark:text-green-400">{{ __('app.public.spent') }}</p>
                                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($project->spent_budget, 2, ',', '.') }} €</p>
                            </div>
                            <div class="rounded-xl bg-amber-50 p-4 text-center dark:bg-amber-900/20">
                                <p class="text-xs font-medium uppercase tracking-wide text-amber-600 dark:text-amber-400">{{ __('app.public.pending') }}</p>
                                <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($project->pending_budget, 2, ',', '.') }} €</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $budgetProgress = $project->budget_progress;
                        @endphp
                        <div class="mb-6">
                            <div class="h-3 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                                <div class="{{ $budgetProgress === 100 ? 'bg-green-500' : 'bg-amber-500' }} h-full rounded-full transition-all duration-500" style="width: {{ $budgetProgress }}%"></div>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ __('app.public.budget_percent_spent', ['percent' => $budgetProgress]) }}
                            </p>
                        </div>

                        <!-- Expenses by Category -->
                        @php
                            $categories = [
                                'material' => ['name' => __('app.public.expense_categories.material'), 'icon' => '🧱', 'color' => 'blue'],
                                'tool' => ['name' => __('app.public.expense_categories.tool'), 'icon' => '🔧', 'color' => 'orange'],
                                'consumable' => ['name' => __('app.public.expense_categories.consumable'), 'icon' => '📦', 'color' => 'gray'],
                                'service' => ['name' => __('app.public.expense_categories.service'), 'icon' => '👷', 'color' => 'green'],
                                'other' => ['name' => __('app.public.expense_categories.other'), 'icon' => '📋', 'color' => 'slate'],
                            ];
                            $groupedExpenses = $project->expenses->groupBy('category');
                        @endphp

                        <div class="space-y-4">
                            @foreach ($groupedExpenses as $category => $expenses)
                                @php
                                    $categoryInfo = $categories[$category] ?? ['name' => ucfirst($category), 'icon' => '📦', 'color' => 'slate'];
                                    $categoryTotal = $expenses->sum('total_price');
                                    $categorySpent = $expenses->where('is_purchased', true)->sum('total_price');
                                @endphp
                                <details class="group rounded-xl border border-slate-200 dark:border-slate-600">
                                    <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xl">{{ $categoryInfo['icon'] }}</span>
                                            <span class="font-semibold text-slate-900 dark:text-white">{{ $categoryInfo['name'] }}</span>
                                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                                {{ $expenses->count() }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">
                                                {{ number_format($categorySpent, 2, ',', '.') }} / {{ number_format($categoryTotal, 2, ',', '.') }} €
                                            </span>
                                            <svg class="h-5 w-5 text-slate-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </summary>
                                    <div class="border-t border-slate-200 p-4 dark:border-slate-600">
                                        <ul class="space-y-2">
                                            @foreach ($expenses as $expense)
                                                <li class="{{ $expense->is_purchased ? 'opacity-60' : '' }} flex items-center justify-between py-2">
                                                    <div class="flex items-center gap-3">
                                                        @if ($expense->is_purchased)
                                                            <svg class="h-5 w-5 shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 shrink-0 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                                                            </svg>
                                                        @endif
                                                        <div>
                                                            <span class="{{ $expense->is_purchased ? 'line-through text-slate-500' : 'text-slate-900 dark:text-white' }}">
                                                                {{ $expense->name }}
                                                            </span>
                                                            @if ($expense->supplier)
                                                                <span class="ml-2 text-xs text-slate-400">{{ $expense->supplier }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <span class="text-sm text-slate-500 dark:text-slate-400">
                                                            {{ $expense->quantity }}{{ $expense->unit ? ' ' . $expense->unit : '' }} × {{ number_format($expense->unit_price, 2, ',', '.') }} €
                                                        </span>
                                                        <span class="{{ $expense->is_purchased ? 'text-green-600 dark:text-green-400' : 'text-slate-900 dark:text-white' }} min-w-20 text-right font-medium">
                                                            {{ number_format($expense->total_price, 2, ',', '.') }} €
                                                        </span>
                                                        @if ($expense->url)
                                                            <a class="text-amber-500 hover:text-amber-600" href="{{ $expense->url }}" target="_blank" rel="noopener">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <!-- Links -->
            @if ($project->links->count() > 0)
                <section class="mb-12">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <h2 class="mb-4 flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            {{ __('app.public.links') }}
                        </h2>
                        <ul class="space-y-3">
                            @foreach ($project->links as $link)
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 shrink-0">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <a class="font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300" href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                            {{ $link->title }}
                                        </a>
                                        @if ($link->description)
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $link->description }}</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            <!-- Downloadable Files -->
            @if ($project->files->where('type', '!=', 'image')->count() > 0)
                <section class="mb-12">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <h2 class="mb-4 flex items-center gap-3 text-xl font-bold text-slate-900 dark:text-white">
                            <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            {{ __('app.public.downloadable_files') }}
                        </h2>
                        <ul class="space-y-3">
                            @foreach ($project->files->where('type', '!=', 'image') as $file)
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 shrink-0">
                                        @if ($file->type === 'stl')
                                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                                            </svg>
                                        @elseif ($file->type === 'pdf')
                                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <a class="font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300" href="{{ $file->url }}" target="_blank" download>
                                            {{ $file->name }}
                                        </a>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} · {{ number_format($file->size / 1024, 0) }} KB
                                            @if ($file->description)
                                                · {{ $file->description }}
                                            @endif
                                        </p>
                                    </div>
                                    <a class="shrink-0 rounded-lg bg-amber-100 p-2 text-amber-600 transition-colors hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50" href="{{ $file->url }}" title="{{ __('app.public.download') }}" download>
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif

            <!-- Diary Entries Timeline -->
            @if ($project->diaryEntries->count() > 0)
                <section class="mt-16">
                    <h2 class="mb-10 flex items-center gap-3 text-2xl font-bold text-slate-900 dark:text-white">
                        <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('app.public.project_diary') }}
                    </h2>

                    <!-- Timeline Container -->
                    <div class="relative">
                        <!-- Vertical Line -->
                        <div class="bg-linear-to-b absolute left-4 top-0 h-full w-0.5 from-amber-400 via-amber-500 to-amber-600 sm:left-1/2 sm:-ml-px"></div>

                        <!-- Timeline Entries -->
                        <div class="space-y-12">
                            @foreach ($project->diaryEntries as $index => $entry)
                                <article class="{{ $index % 2 === 0 ? 'sm:flex-row-reverse' : '' }} relative flex flex-col sm:flex-row">
                                    <!-- Timeline Node -->
                                    <div class="absolute left-4 -ml-2 flex h-4 w-4 items-center justify-center sm:left-1/2 sm:-ml-2">
                                        <div class="h-4 w-4 rounded-full border-4 border-amber-500 bg-white shadow-md dark:bg-slate-900"></div>
                                        <div class="absolute h-3 w-3 animate-ping rounded-full bg-amber-400 opacity-20"></div>
                                    </div>

                                    <!-- Date Badge (Mobile: inline, Desktop: opposite side) -->
                                    <div class="{{ $index % 2 === 0 ? 'sm:pl-8 sm:text-left' : 'sm:pr-8 sm:text-right' }} mb-3 ml-10 sm:mb-0 sm:ml-0 sm:w-1/2">
                                        <time class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $entry->entry_date->format('d M Y') }}
                                        </time>
                                    </div>

                                    <!-- Content Card -->
                                    <div class="{{ $index % 2 === 0 ? 'sm:pr-8' : 'sm:pl-8' }} ml-10 sm:ml-0 sm:w-1/2">
                                        <div class="group relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:border-amber-200 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800 dark:hover:border-amber-800">
                                            <!-- Entry Type Indicator -->
                                            @if ($entry->type)
                                                <div class="{{ $index % 2 === 0 ? 'right-4' : 'left-4' }} absolute -top-2">
                                                    @php
                                                        $typeConfig = match ($entry->type) {
                                                            'progress' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'label' => __('app.public.entry_types.progress')],
                                                            'issue' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'label' => __('app.public.entry_types.issue')],
                                                            'solution' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'label' => __('app.public.entry_types.solution')],
                                                            'milestone' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-700 dark:text-purple-400', 'label' => __('app.public.entry_types.milestone')],
                                                            'note' => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'label' => __('app.public.entry_types.note')],
                                                            default => ['bg' => 'bg-slate-100 dark:bg-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'label' => ucfirst($entry->type)],
                                                        };
                                                    @endphp
                                                    <span class="{{ $typeConfig['bg'] }} {{ $typeConfig['text'] }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                                        {{ $typeConfig['label'] }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Title -->
                                            @if ($entry->title)
                                                <h3 class="mb-3 text-lg font-semibold text-slate-900 dark:text-white">
                                                    {{ $entry->title }}
                                                </h3>
                                            @endif

                                            <!-- Content -->
                                            <div class="prose prose-slate prose-sm dark:prose-invert max-w-none">
                                                {!! $entry->content !!}
                                            </div>

                                            <!-- Images -->
                                            @if ($entry->images->count() > 0)
                                                <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                                    @foreach ($entry->images as $image)
                                                        <a href="{{ $image->url }}" target="_blank" class="block overflow-hidden rounded-lg">
                                                            <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}" loading="lazy" class="h-32 w-full object-cover transition-transform duration-300 hover:scale-105">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Footer: Time spent -->
                                            @if ($entry->time_spent_minutes)
                                                <div class="mt-4 flex items-center gap-2 border-t border-slate-100 pt-4 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ __('app.public.time_dedicated', ['hours' => floor($entry->time_spent_minutes / 60), 'minutes' => $entry->time_spent_minutes % 60]) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Timeline End Node -->
                        <div class="absolute bottom-0 left-4 -ml-3 h-6 w-6 rounded-full border-4 border-amber-600 bg-amber-500 shadow-lg sm:left-1/2 sm:-ml-3">
                            <svg class="h-full w-full p-0.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </section>
            @endif
        </article>
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="font-semibold text-slate-900 dark:text-white">Build Diary</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('app.public.published_with') }}
                </p>
            </div>
        </div>
    </footer>
</body>

</html>

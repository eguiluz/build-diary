<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('app.public.projects_of', ['name' => $user->name]) }} - {{ config('app.name', 'Build Diary') }}</title>
    <meta name="description" content="{{ __('app.public.gallery_meta_description', ['name' => $user->name]) }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ __('app.public.projects_of', ['name' => $user->name]) }}">
    <meta property="og:description" content="{{ __('app.public.gallery_meta_description', ['name' => $user->name]) }}">
    <meta property="og:type" content="website">

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
        <nav class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-2 transition-opacity hover:opacity-80" href="{{ url('/') }}">
                    <svg class="h-7 w-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">Build Diary</span>
                </a>

                <a class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white" href="{{ url('/') }}">
                    {{ __('app.public.back_to_home') }}
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Gallery Header -->
        <div class="mb-10 text-center">
            <div class="bg-linear-to-br mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full from-amber-400 to-orange-500 text-3xl font-bold text-white shadow-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h1 class="mb-2 text-3xl font-bold text-slate-900 dark:text-white">
                {{ __('app.public.projects_of', ['name' => $user->name]) }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400">
                {{ trans_choice('app.public.public_projects_count', $projects->count(), ['count' => $projects->count()]) }}
            </p>
            @if ($projects->count() > 0)
                <div class="mt-4">
                    <a class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-amber-600" href="{{ route('public.book', $user) }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('app.public.download_book') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Projects Grid -->
        @if ($projects->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($projects as $project)
                    <a class="group block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:border-amber-400 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800 dark:hover:border-amber-500" href="{{ route('public.project.show', $project->slug) }}">
                        {{-- Imagen de portada --}}
                        <div class="aspect-4/3 relative overflow-hidden bg-slate-100 dark:bg-slate-900">
                            @if ($project->files->first())
                                <img class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" src="{{ Storage::url($project->files->first()->path) }}" alt="{{ $project->title }}">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-16 w-16 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Badge de estado --}}
                            @if ($project->status)
                                <div class="absolute left-3 top-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }}">
                                        {{ $project->status->name }}
                                    </span>
                                </div>
                            @endif

                            {{-- Contador de imágenes --}}
                            @if ($project->images_count > 0)
                                <div class="absolute bottom-3 right-3 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $project->images_count }}
                                </div>
                            @endif
                        </div>

                        {{-- Info del proyecto --}}
                        <div class="p-4">
                            <h3 class="line-clamp-1 font-semibold text-slate-900 group-hover:text-amber-600 dark:text-white dark:group-hover:text-amber-400">
                                {{ $project->title }}
                            </h3>

                            @if ($project->description)
                                <p class="mt-1 line-clamp-2 text-sm text-slate-500 dark:text-slate-400">
                                    {{ strip_tags($project->description) }}
                                </p>
                            @endif

                            {{-- Tags --}}
                            @if ($project->tags->count() > 0)
                                <div class="mt-3 flex flex-wrap gap-1">
                                    @foreach ($project->tags->take(3) as $tag)
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if ($project->tags->count() > 3)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                            +{{ $project->tags->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-3 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
                                <span>{{ $project->updated_at->diffForHumans() }}</span>
                                @if ($project->category)
                                    <span class="rounded bg-slate-100 px-2 py-0.5 dark:bg-slate-700">
                                        {{ $project->category }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">{{ __('app.public.no_public_projects') }}</h3>
                <p class="mt-1 text-slate-500 dark:text-slate-400">
                    {{ __('app.public.no_public_projects_desc') }}
                </p>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 py-8 dark:border-slate-800">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm text-slate-500 sm:px-6 lg:px-8 dark:text-slate-400">
            <a class="transition-colors hover:text-amber-500" href="{{ url('/') }}">
                Build Diary
            </a>
            · {{ __('app.public.tagline') }}
        </div>
    </footer>
</body>

</html>

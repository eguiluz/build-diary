<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Build Diary') }} - {{ __('app.welcome.page_title') }}</title>
    <meta name="description" content="{{ __('app.welcome.meta_description') }}">

    @include('partials.favicon')

    <!-- Fonts -->
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-linear-to-br min-h-screen from-slate-50 to-slate-100 font-sans antialiased dark:from-slate-900 dark:to-slate-800">
    <!-- Navigation -->
    <header class="w-full">
        <nav class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-xl font-bold text-slate-900 dark:text-white">Build Diary</span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 font-medium text-white transition-colors hover:bg-amber-600" href="{{ url('/admin') }}">
                            {{ __('app.welcome.go_to_panel') }}
                        </a>
                    @else
                        <a class="font-medium text-slate-600 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white" href="{{ url('/admin/login') }}">
                            {{ __('app.welcome.login') }}
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="mx-auto max-w-7xl px-4 pb-24 pt-16 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="mb-6 text-4xl font-bold text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                {{ __('app.welcome.hero_title') }}
                <span class="text-amber-500">{{ __('app.welcome.hero_title_highlight') }}</span>
            </h1>
            <p class="mx-auto mb-10 max-w-3xl text-xl text-slate-600 dark:text-slate-300">
                {{ __('app.welcome.hero_subtitle') }}
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                @auth
                    <a class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-8 py-4 text-lg font-semibold text-white shadow-lg shadow-amber-500/25 transition-all hover:scale-105 hover:bg-amber-600" href="{{ url('/admin') }}">
                        {{ __('app.welcome.go_to_panel') }}
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-8 py-4 text-lg font-semibold text-white shadow-lg shadow-amber-500/25 transition-all hover:scale-105 hover:bg-amber-600" href="{{ url('/admin/login') }}">
                        {{ __('app.welcome.login') }}
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="mb-4 text-3xl font-bold text-slate-900 sm:text-4xl dark:text-white">
                {{ __('app.welcome.features_title') }}
            </h2>
            <p class="mx-auto max-w-2xl text-lg text-slate-600 dark:text-slate-400">
                {{ __('app.welcome.features_subtitle') }}
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <!-- Feature 1: Proyectos -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_projects_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_projects_desc') }}
                </p>
            </div>

            <!-- Feature 2: Diario -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_diary_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_diary_desc') }}
                </p>
            </div>

            <!-- Feature 3: Archivos -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_files_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_files_desc') }}
                </p>
            </div>

            <!-- Feature 4: Calendario -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/30">
                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_calendar_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_calendar_desc') }}
                </p>
            </div>

            <!-- Feature 5: Contactos -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 dark:bg-rose-900/30">
                    <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_contacts_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_contacts_desc') }}
                </p>
            </div>

            <!-- Feature 6: Tags -->
            <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100 dark:bg-cyan-900/30">
                    <svg class="h-6 w-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-white">{{ __('app.welcome.feature_tags_title') }}</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    {{ __('app.welcome.feature_tags_desc') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="bg-slate-900 py-24 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div>
                    <h2 class="mb-6 text-3xl font-bold text-white sm:text-4xl">
                        {{ __('app.welcome.why_title') }}
                    </h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-1 text-lg font-semibold text-white">{{ __('app.welcome.benefit_context_title') }}</h3>
                                <p class="text-slate-400">{{ __('app.welcome.benefit_context_desc') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-1 text-lg font-semibold text-white">{{ __('app.welcome.benefit_centralized_title') }}</h3>
                                <p class="text-slate-400">{{ __('app.welcome.benefit_centralized_desc') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-1 text-lg font-semibold text-white">{{ __('app.welcome.benefit_personal_title') }}</h3>
                                <p class="text-slate-400">{{ __('app.welcome.benefit_personal_desc') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-500">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="mb-1 text-lg font-semibold text-white">{{ __('app.welcome.benefit_reminders_title') }}</h3>
                                <p class="text-slate-400">{{ __('app.welcome.benefit_reminders_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-linear-to-br rounded-2xl from-amber-500 to-orange-600 p-8 lg:p-12">
                        <div class="rounded-xl bg-white p-6 shadow-2xl dark:bg-slate-800">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                                <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 w-3/4 rounded bg-slate-200 dark:bg-slate-700"></div>
                                <div class="h-4 w-full rounded bg-slate-200 dark:bg-slate-700"></div>
                                <div class="h-4 w-5/6 rounded bg-slate-200 dark:bg-slate-700"></div>
                                <div class="mt-6 grid grid-cols-3 gap-3">
                                    <div class="h-20 rounded-lg bg-amber-100 dark:bg-amber-900/30"></div>
                                    <div class="h-20 rounded-lg bg-blue-100 dark:bg-blue-900/30"></div>
                                    <div class="h-20 rounded-lg bg-green-100 dark:bg-green-900/30"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        <div class="bg-linear-to-r rounded-3xl from-amber-500 to-orange-500 p-12 text-center lg:p-16">
            <h2 class="mb-4 text-3xl font-bold text-white sm:text-4xl">
                {{ __('app.welcome.cta_title') }}
            </h2>
            <p class="mx-auto mb-8 max-w-2xl text-xl text-amber-100">
                {{ __('app.welcome.cta_subtitle') }}
            </p>
            @guest
                <a class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-lg font-semibold text-amber-600 shadow-lg transition-all hover:scale-105" href="{{ url('/admin/login') }}">
                    {{ __('app.welcome.login') }}
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <a class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-lg font-semibold text-amber-600 shadow-lg transition-all hover:scale-105" href="{{ url('/admin') }}">
                    {{ __('app.welcome.go_to_panel') }}
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 dark:border-slate-800">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-2">
                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-lg font-semibold text-slate-900 dark:text-white">Build Diary</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('app.welcome.footer_made_with') }}
                </p>
                <p class="text-sm text-slate-400 dark:text-slate-500">
                    {!! __('app.welcome.footer_rights', ['year' => date('Y')]) !!}
                </p>
            </div>
        </div>
    </footer>
</body>

</html>

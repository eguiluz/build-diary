<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($this->getProjects() as $project)
            <a class="hover:border-primary-500 dark:hover:border-primary-500 group block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-200 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800" href="{{ route('filament.admin.resources.projects.edit', $project) }}">
                {{-- Imagen de portada --}}
                <div class="aspect-4/3 relative overflow-hidden bg-gray-100 dark:bg-gray-900">
                    @if ($project->files->first())
                        <img class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" src="{{ Storage::url($project->files->first()->path) }}" alt="{{ $project->title }}">
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <x-heroicon-o-photo class="h-16 w-16 text-gray-300 dark:text-gray-600" />
                        </div>
                    @endif

                    {{-- Badge de estado --}}
                    <div class="absolute left-3 top-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: {{ $project->status->color }}20; color: {{ $project->status->color }}">
                            {{ $project->status->name }}
                        </span>
                    </div>

                    {{-- Contador de imágenes --}}
                    @if ($project->images_count > 0)
                        <div class="absolute bottom-3 right-3 flex items-center gap-1 rounded-lg bg-black/60 px-2 py-1 text-xs text-white">
                            <x-heroicon-o-photo class="h-3.5 w-3.5" />
                            {{ $project->images_count }}
                        </div>
                    @endif
                </div>

                {{-- Info del proyecto --}}
                <div class="p-4">
                    <h3 class="group-hover:text-primary-600 dark:group-hover:text-primary-400 line-clamp-1 font-semibold text-gray-900 dark:text-white">
                        {{ $project->title }}
                    </h3>

                    @if ($project->description)
                        <p class="mt-1 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ strip_tags(Str::markdown($project->description)) }}
                        </p>
                    @endif

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                        <span>{{ $project->updated_at->diffForHumans() }}</span>
                        @if ($project->category)
                            <span class="rounded bg-gray-100 px-2 py-0.5 dark:bg-gray-700">
                                {{ $project->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="py-12 text-center">
                    <x-heroicon-o-photo class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ __('app.filament.gallery.no_projects') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('app.filament.gallery.no_projects_desc') }}
                    </p>
                    <div class="mt-6">
                        <a class="bg-primary-600 hover:bg-primary-500 focus:bg-primary-500 active:bg-primary-700 focus:ring-primary-500 inline-flex items-center rounded-lg border border-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2" href="{{ route('filament.admin.resources.projects.create') }}">
                            <x-heroicon-o-plus class="mr-2 h-4 w-4" />
                            {{ __('app.filament.gallery.new_project') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>

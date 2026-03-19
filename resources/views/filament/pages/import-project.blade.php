<x-filament-panels::page>
    <form wire:submit="import">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-arrow-up-tray">
                {{ __('app.filament.import.button') }}
            </x-filament::button>
        </div>
    </form>

    <x-filament::section class="mt-8">
        <x-slot name="heading">
            {{ __('app.filament.import.info_heading') }}
        </x-slot>

        <div class="prose dark:prose-invert max-w-none text-sm">
            <p>{{ __('app.filament.import.info_intro') }}</p>
            <ul>
                <li><strong>{{ __('app.filament.import.info_project_data') }}:</strong> {{ __('app.filament.import.info_project_data_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_person') }}:</strong> {{ __('app.filament.import.info_person_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_tags') }}:</strong> {{ __('app.filament.import.info_tags_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_files') }}:</strong> {{ __('app.filament.import.info_files_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_diary') }}:</strong> {{ __('app.filament.import.info_diary_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_links') }}:</strong> {{ __('app.filament.import.info_links_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_tasks') }}:</strong> {{ __('app.filament.import.info_tasks_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_expenses') }}:</strong> {{ __('app.filament.import.info_expenses_desc') }}</li>
                <li><strong>{{ __('app.filament.import.info_events') }}:</strong> {{ __('app.filament.import.info_events_desc') }}</li>
            </ul>

            <p class="text-gray-500 dark:text-gray-400">
                <strong>{{ __('app.common.note') }}:</strong> {{ __('app.filament.import.info_note') }}
            </p>
        </div>
    </x-filament::section>
</x-filament-panels::page>

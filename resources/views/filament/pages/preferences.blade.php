<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Guardar preferencias
            </x-filament::button>
        </div>
    </form>

    @script
        <script>
            $wire.on('theme-changed', ({
                theme
            }) => {
                // Update localStorage for Filament's theme toggle
                if (theme === 'dark') {
                    localStorage.setItem('theme', 'dark');
                } else if (theme === 'light') {
                    localStorage.setItem('theme', 'light');
                } else {
                    localStorage.removeItem('theme');
                }

                // Reload the page to apply the new theme properly
                window.location.reload();
            });
        </script>
    @endscript
</x-filament-panels::page>

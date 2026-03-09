<x-filament-panels::page>
    <form wire:submit="import">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-arrow-up-tray">
                Importar proyecto
            </x-filament::button>
        </div>
    </form>

    <x-filament::section class="mt-8">
        <x-slot name="heading">
            Información sobre la importación
        </x-slot>

        <div class="prose dark:prose-invert max-w-none text-sm">
            <p>Al importar un proyecto se incluirán:</p>
            <ul>
                <li><strong>Datos del proyecto:</strong> título, descripción, categoría, fechas, etc.</li>
                <li><strong>Persona asociada:</strong> se buscará por nombre o se creará si no existe.</li>
                <li><strong>Etiquetas:</strong> se buscarán por nombre o se crearán si no existen.</li>
                <li><strong>Archivos:</strong> imágenes y documentos adjuntos.</li>
                <li><strong>Entradas de diario:</strong> todas las entradas con sus fechas.</li>
                <li><strong>Enlaces:</strong> URLs y referencias externas.</li>
                <li><strong>Tareas:</strong> lista de tareas con su estado.</li>
                <li><strong>Gastos:</strong> registro de gastos del proyecto.</li>
                <li><strong>Eventos:</strong> eventos de calendario asociados.</li>
            </ul>

            <p class="text-gray-500 dark:text-gray-400">
                <strong>Nota:</strong> El proyecto importado siempre será privado por seguridad.
                El título se modificará añadiendo "(importado)" para identificarlo.
            </p>
        </div>
    </x-filament::section>
</x-filament-panels::page>

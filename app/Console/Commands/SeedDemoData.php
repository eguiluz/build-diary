<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedDemoData extends Command
{
    protected $signature = 'app:seed-demo
                            {--force : Cargar datos sin confirmación}
                            {--fresh : Ejecutar migrate:fresh antes de cargar datos}';

    protected $description = 'Carga datos de demostración en la base de datos';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║          BUILD DIARY - Datos de Demostración             ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->info('');

        $this->warn('Este comando cargará los siguientes datos de ejemplo:');
        $this->info('');
        $this->line('  • 5 personas con información de contacto y cumpleaños');
        $this->line('  • 10 etiquetas con colores personalizados');
        $this->line('  • 8 proyectos (carpintería, impresión 3D, arte en papel)');
        $this->line('  • Entradas de diario y enlaces para cada proyecto');
        $this->line('  • 7 eventos de calendario (deadlines, recordatorios, etc.)');
        $this->info('');

        if ($this->option('fresh')) {
            $this->warn('⚠️  La opción --fresh eliminará TODOS los datos existentes.');
            $this->info('');
        }

        if (! $this->option('force')) {
            if (! $this->confirm('¿Deseas continuar?', true)) {
                $this->info('Operación cancelada.');

                return self::SUCCESS;
            }
        }

        if ($this->option('fresh')) {
            $this->info('');
            $this->info('Ejecutando migrate:fresh...');
            $this->call('migrate:fresh');
            $this->info('');
        }

        // Asegurar que existan los estados y el usuario admin
        $this->info('Preparando datos base...');
        $this->callSilently('db:seed', ['--class' => 'ProjectStatusSeeder']);
        $this->callSilently('db:seed', ['--class' => 'ProjectCategorySeeder']);
        $this->callSilently('db:seed', ['--class' => 'AdminUserSeeder']);

        // Cargar datos de demo
        $this->call('db:seed', ['--class' => 'DemoSeeder']);

        return self::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder maestro para datos de demostración.
 *
 * Ejecutar con: sail artisan db:seed --class=DemoSeeder
 *
 * Este seeder carga datos de ejemplo para probar la aplicación:
 * - 5 personas con cumpleaños y notas
 * - 10 etiquetas de colores
 * - 8 proyectos con entradas de diario y enlaces
 * - 7 eventos de calendario
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║     CARGANDO DATOS DE DEMOSTRACIÓN      ║');
        $this->command->info('╚══════════════════════════════════════════╝');
        $this->command->info('');

        $this->call([
            DemoPeopleSeeder::class,
            DemoTagsSeeder::class,
            DemoProjectsSeeder::class,
            DemoProjectFilesSeeder::class,
            DemoProjectTasksSeeder::class,
            DemoProjectExpensesSeeder::class,
            DemoCalendarEventsSeeder::class,
            DemoInventorySeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('✓ Datos de demostración cargados correctamente.');
        $this->command->info('');
        $this->command->info('Accede al panel: http://localhost/admin');
        $this->command->info('Usuario: admin@builddiary.test');
        $this->command->info('Contraseña: password');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('');
    }
}

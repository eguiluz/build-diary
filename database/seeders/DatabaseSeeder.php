<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder principal de la aplicación.
 *
 * Uso básico:
 *   sail artisan db:seed
 *
 * Para cargar datos de demostración (opcional):
 *   sail artisan db:seed --class=DemoSeeder
 */
final class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ProjectStatusSeeder::class,
            ProjectCategorySeeder::class,
            AdminUserSeeder::class,
        ]);

        if ($this->command->confirm('¿Deseas cargar datos de demostración?', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

final class ProjectStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Idea',
                'slug' => 'idea',
                'color' => '#9CA3AF',
                'order' => 0,
                'is_default' => true,
                'is_completed' => false,
            ],
            [
                'name' => 'Planificación',
                'slug' => 'planificacion',
                'color' => '#3B82F6',
                'order' => 1,
                'is_default' => false,
                'is_completed' => false,
            ],
            [
                'name' => 'En progreso',
                'slug' => 'en-progreso',
                'color' => '#F59E0B',
                'order' => 2,
                'is_default' => false,
                'is_completed' => false,
            ],
            [
                'name' => 'En pausa',
                'slug' => 'en-pausa',
                'color' => '#EF4444',
                'order' => 3,
                'is_default' => false,
                'is_completed' => false,
            ],
            [
                'name' => 'Completado',
                'slug' => 'completado',
                'color' => '#10B981',
                'order' => 4,
                'is_default' => false,
                'is_completed' => true,
            ],
            [
                'name' => 'Cancelado',
                'slug' => 'cancelado',
                'color' => '#6B7280',
                'order' => 5,
                'is_default' => false,
                'is_completed' => true,
            ],
        ];

        foreach ($statuses as $status) {
            ProjectStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Database\Seeder;

class DemoProjectTasksSeeder extends Seeder
{
    /**
     * Tareas por categoría de proyecto.
     *
     * @var array<string, array<int, array{title: string, description?: string, completed: bool}>>
     */
    private array $tasksByCategory = [
        'carpentry' => [
            ['title' => 'Comprar materiales', 'description' => 'Madera, tornillos, cola', 'completed' => true],
            ['title' => 'Preparar herramientas', 'completed' => true],
            ['title' => 'Cortar piezas a medida', 'completed' => true],
            ['title' => 'Lijar superficies', 'description' => 'Progresivo: 80, 120, 180', 'completed' => true],
            ['title' => 'Ensamblar estructura', 'completed' => false],
            ['title' => 'Aplicar acabado', 'description' => 'Aceite o barniz', 'completed' => false],
            ['title' => 'Instalar en su ubicación', 'completed' => false],
        ],
        '3d_printing' => [
            ['title' => 'Diseñar modelo CAD', 'completed' => true],
            ['title' => 'Exportar STL', 'completed' => true],
            ['title' => 'Configurar laminado', 'description' => 'Ajustar parámetros en PrusaSlicer', 'completed' => true],
            ['title' => 'Imprimir prototipo', 'completed' => true],
            ['title' => 'Revisar dimensiones', 'completed' => false],
            ['title' => 'Imprimir versión final', 'completed' => false],
            ['title' => 'Post-procesado', 'description' => 'Lijar, pintar si necesario', 'completed' => false],
        ],
        'paper_art' => [
            ['title' => 'Diseñar patrón', 'completed' => true],
            ['title' => 'Preparar materiales', 'description' => 'Papel, cúter, regla', 'completed' => true],
            ['title' => 'Cortar piezas', 'completed' => false],
            ['title' => 'Plegar elementos', 'completed' => false],
            ['title' => 'Ensamblar', 'completed' => false],
        ],
    ];

    public function run(): void
    {
        $projects = Project::with('category')
            ->whereHas('category', function ($query) {
                $query->whereIn('slug', array_keys($this->tasksByCategory));
            })
            ->get();

        if ($projects->isEmpty()) {
            $this->command->warn('No hay proyectos para añadir tareas. Ejecuta DemoProjectsSeeder primero.');

            return;
        }

        $totalTasks = 0;

        foreach ($projects as $project) {
            $categorySlug = $project->category->slug ?? null;
            $categoryTasks = $this->tasksByCategory[$categorySlug] ?? [];

            if (empty($categoryTasks)) {
                continue;
            }

            foreach ($categoryTasks as $order => $taskData) {
                $existingTask = ProjectTask::where('project_id', $project->id)
                    ->where('title', $taskData['title'])
                    ->first();

                if ($existingTask) {
                    continue;
                }

                ProjectTask::create([
                    'project_id' => $project->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'is_completed' => $taskData['completed'],
                    'completed_at' => $taskData['completed'] ? now()->subDays(rand(1, 10)) : null,
                    'order' => $order,
                ]);

                $totalTasks++;
            }
        }

        $this->command->info("✓ {$totalTasks} tareas de demo añadidas a los proyectos.");
    }
}

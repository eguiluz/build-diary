<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DemoProjectFilesSeeder extends Seeder
{
    /**
     * Imágenes de demo por categoría de proyecto.
     * Usamos IDs específicos de Picsum para consistencia.
     *
     * @var array<string, array<int, array{id: int, description: string}>>
     */
    private array $imagesByCategory = [
        'carpentry' => [
            ['id' => 1003, 'description' => 'Tablero de roble antes del tratamiento'],
            ['id' => 1015, 'description' => 'Detalle de la veta de la madera'],
            ['id' => 1018, 'description' => 'Herramientas utilizadas'],
            ['id' => 1024, 'description' => 'Proceso de lijado'],
        ],
        '3d_printing' => [
            ['id' => 367, 'description' => 'Diseño CAD del modelo'],
            ['id' => 180, 'description' => 'Impresora en funcionamiento'],
            ['id' => 196, 'description' => 'Pieza recién impresa'],
            ['id' => 201, 'description' => 'Detalle de las capas'],
        ],
        'paper_art' => [
            ['id' => 24, 'description' => 'Boceto inicial del diseño'],
            ['id' => 42, 'description' => 'Materiales preparados'],
            ['id' => 106, 'description' => 'Proceso de corte'],
        ],
    ];

    public function run(): void
    {
        $projects = Project::with('category')
            ->whereHas('category', function ($query) {
                $query->whereIn('slug', array_keys($this->imagesByCategory));
            })
            ->get();

        if ($projects->isEmpty()) {
            $this->command->warn('No hay proyectos para añadir imágenes. Ejecuta DemoProjectsSeeder primero.');

            return;
        }

        // Asegurar que existe el directorio
        Storage::disk('public')->makeDirectory('projects');

        $totalImages = 0;

        foreach ($projects as $project) {
            $categorySlug = $project->category->slug ?? null;
            $categoryImages = $this->imagesByCategory[$categorySlug] ?? [];

            if (empty($categoryImages)) {
                continue;
            }

            // Seleccionar 2-3 imágenes aleatorias para este proyecto
            $selectedImages = collect($categoryImages)
                ->shuffle()
                ->take(rand(2, min(3, count($categoryImages))));

            foreach ($selectedImages as $order => $imageData) {
                $existingFile = ProjectFile::where('project_id', $project->id)
                    ->where('description', $imageData['description'])
                    ->first();

                if ($existingFile) {
                    continue;
                }

                $filename = $this->downloadImage($imageData['id'], $project->id);

                if ($filename) {
                    ProjectFile::create([
                        'project_id' => $project->id,
                        'name' => $filename,
                        'original_name' => "demo-image-{$imageData['id']}.jpg",
                        'path' => "projects/{$filename}",
                        'disk' => 'public',
                        'mime_type' => 'image/jpeg',
                        'size' => Storage::disk('public')->size("projects/{$filename}"),
                        'type' => ProjectFile::TYPE_IMAGE,
                        'description' => $imageData['description'],
                        'order' => $order,
                    ]);

                    $totalImages++;
                }
            }
        }

        $this->command->info("✓ {$totalImages} imágenes de demo añadidas a los proyectos.");
    }

    /**
     * Descarga una imagen de Picsum Photos.
     */
    private function downloadImage(int $picsumId, int $projectId): ?string
    {
        try {
            $response = Http::timeout(30)->get("https://picsum.photos/id/{$picsumId}/800/600");

            if (! $response->successful()) {
                // Si falla el ID específico, intentar con uno aleatorio
                $response = Http::timeout(30)->get('https://picsum.photos/800/600');
            }

            if ($response->successful()) {
                $filename = "project-{$projectId}-".uniqid().'.jpg';
                Storage::disk('public')->put("projects/{$filename}", $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            $this->command->warn("No se pudo descargar imagen {$picsumId}: ".$e->getMessage());
        }

        return null;
    }
}

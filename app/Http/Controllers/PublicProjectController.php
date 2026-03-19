<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

final class PublicProjectController extends Controller
{
    public function show(string $slug): View
    {
        $project = $this->getProject($slug);

        return view('public.projects.show', compact('project'));
    }

    public function pdf(string $slug): Response
    {
        $project = $this->getProject($slug);

        $pdf = Pdf::loadView('public.projects.pdf', compact('project'))
            ->setPaper('a4')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true);

        $filename = $project->slug.'.pdf';

        return $pdf->download($filename);
    }

    public function zip(string $slug): BinaryFileResponse
    {
        $project = $this->getProject($slug);

        $zipFileName = $project->slug.'.zip';
        $zipPath = storage_path('app/temp/'.$zipFileName);

        // Ensure temp directory exists
        if (! is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Add README.md
        $readme = $this->generateReadme($project);
        $zip->addFromString('README.md', $readme);

        // Add project description as markdown
        if ($project->description) {
            $zip->addFromString('descripcion.md', "# {$project->title}\n\n{$project->description}");
        }

        // Add diary entries as markdown
        if ($project->diaryEntries->count() > 0) {
            $diary = $this->generateDiaryMarkdown($project);
            $zip->addFromString('diario.md', $diary);
        }

        // Add checklist as markdown
        if ($project->tasks->count() > 0) {
            $checklist = $this->generateChecklistMarkdown($project);
            $zip->addFromString('checklist.md', $checklist);
        }

        // Add budget as markdown
        if ($project->expenses->count() > 0) {
            $budget = $this->generateBudgetMarkdown($project);
            $zip->addFromString('presupuesto.md', $budget);
        }

        // Add all files organized by type
        foreach ($project->files as $index => $file) {
            $filePath = Storage::disk($file->disk)->path($file->path);
            if (file_exists($filePath)) {
                $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);

                // Build filename: use name field but ensure it has the correct extension
                $nameWithoutExt = pathinfo($file->name ?: $file->original_name, PATHINFO_FILENAME);
                $baseName = $nameWithoutExt.'.'.$extension;

                // Organize files by type
                $folder = match ($file->type) {
                    'image' => 'imagenes',
                    'stl' => 'modelos-3d',
                    'pdf' => 'documentos',
                    default => 'adjuntos',
                };

                $zip->addFile($filePath, $folder.'/'.$baseName);
            }
        }

        // Add diary entry images
        foreach ($project->diaryEntries as $entry) {
            foreach ($entry->images as $imageIndex => $image) {
                $imagePath = Storage::disk($image->disk)->path($image->path);
                if (file_exists($imagePath)) {
                    $extension = pathinfo($image->original_name, PATHINFO_EXTENSION);
                    $baseName = $entry->entry_date->format('Y-m-d').'_'.($imageIndex + 1).'.'.$extension;
                    $zip->addFile($imagePath, 'diario/'.$baseName);
                }
            }
        }

        // Generate and add PDF
        $pdf = Pdf::loadView('public.projects.pdf', compact('project'))
            ->setPaper('a4')
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true);

        $pdfContent = $pdf->output();
        $zip->addFromString($project->slug.'.pdf', $pdfContent);

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    private function getProject(string $slug): Project
    {
        return Project::query()
            ->where('slug', $slug)
            ->public()
            ->with([
                'status',
                'category',
                'tags',
                'person',
                'files' => fn ($q) => $q->orderBy('order'),
                'tasks' => fn ($q) => $q->orderBy('order'),
                'expenses' => fn ($q) => $q->orderBy('category')->orderBy('name'),
                'links' => fn ($q) => $q->orderBy('order'),
                'diaryEntries' => fn ($q) => $q->latest('entry_date')->limit(10)->with('images'),
            ])
            ->firstOrFail();
    }

    private function generateReadme(Project $project): string
    {
        $readme = "# {$project->title}\n\n";
        $readme .= "**Estado**: {$project->status->name}\n";

        if ($project->category !== null) {
            $readme .= "**Categoría**: {$project->category->name}\n";
        }

        if ($project->tags->count() > 0) {
            $tags = $project->tags->pluck('name')->implode(', ');
            $readme .= "**Etiquetas**: {$tags}\n";
        }

        $readme .= "\n## Fechas\n\n";

        if ($project->started_at !== null) {
            $readme .= "- **Iniciado**: {$project->started_at->format('d/m/Y')}\n";
        }

        if ($project->due_date !== null) {
            $readme .= "- **Fecha límite**: {$project->due_date->format('d/m/Y')}\n";
        }

        if ($project->completed_at !== null) {
            $readme .= "- **Completado**: {$project->completed_at->format('d/m/Y')}\n";
        }

        $readme .= "\n## Contenido del ZIP\n\n";
        $readme .= "- `README.md` - Este archivo\n";
        $readme .= "- `descripcion.md` - Descripción completa del proyecto\n";
        $readme .= "- `diario.md` - Entradas del diario del proyecto\n";
        $readme .= "- `checklist.md` - Lista de tareas del proyecto\n";
        $readme .= "- `presupuesto.md` - Presupuesto y gastos del proyecto\n";
        $readme .= "- `imagenes/` - Galería de imágenes del proyecto\n";
        $readme .= "- `{$project->slug}.pdf` - Versión PDF del proyecto\n";

        $readme .= "\n---\n\n";
        $readme .= 'Exportado desde Build Diary el '.now()->format('d/m/Y H:i')."\n";

        return $readme;
    }

    private function generateDiaryMarkdown(Project $project): string
    {
        $diary = "# Diario del proyecto: {$project->title}\n\n";

        foreach ($project->diaryEntries as $entry) {
            $diary .= "## {$entry->entry_date->format('d/m/Y')}";

            if ($entry->title) {
                $diary .= " - {$entry->title}";
            }

            $diary .= "\n\n";

            if ($entry->type) {
                $typeLabel = match ($entry->type) {
                    'progress' => '📈 Progreso',
                    'issue' => '⚠️ Problema',
                    'solution' => '✅ Solución',
                    'milestone' => '🎯 Hito',
                    'note' => '📝 Nota',
                    default => ucfirst($entry->type),
                };
                $diary .= "**Tipo**: {$typeLabel}\n\n";
            }

            $diary .= $entry->content."\n\n";

            if ($entry->time_spent_minutes) {
                $hours = floor($entry->time_spent_minutes / 60);
                $minutes = $entry->time_spent_minutes % 60;
                $diary .= "_Tiempo dedicado: {$hours}h {$minutes}m_\n\n";
            }

            $diary .= "---\n\n";
        }

        return $diary;
    }

    private function generateChecklistMarkdown(Project $project): string
    {
        $completedTasks = $project->tasks->where('is_completed', true)->count();
        $totalTasks = $project->tasks->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $checklist = "# Checklist: {$project->title}\n\n";
        $checklist .= "**Progreso**: {$completedTasks} / {$totalTasks} tareas completadas ({$progress}%)\n\n";
        $checklist .= "---\n\n";

        foreach ($project->tasks as $task) {
            $checkbox = $task->is_completed ? '[x]' : '[ ]';
            $checklist .= "- {$checkbox} {$task->title}";

            if ($task->description) {
                $checklist .= "\n  > {$task->description}";
            }

            if ($task->is_completed && $task->completed_at) {
                $checklist .= "\n  _Completada: {$task->completed_at->format('d/m/Y')}_";
            }

            $checklist .= "\n";
        }

        return $checklist;
    }

    private function generateBudgetMarkdown(Project $project): string
    {
        $budget = "# Presupuesto: {$project->title}\n\n";

        // Summary
        $budget .= "## Resumen\n\n";
        $budget .= "| Concepto | Importe |\n";
        $budget .= "|----------|---------|\n";
        $budget .= '| **Total presupuesto** | '.number_format($project->total_budget, 2, ',', '.')." € |\n";
        $budget .= '| **Gastado** | '.number_format($project->spent_budget, 2, ',', '.')." € |\n";
        $budget .= '| **Pendiente** | '.number_format($project->pending_budget, 2, ',', '.')." € |\n";
        $budget .= "| **Progreso** | {$project->budget_progress}% |\n\n";

        // Group by category
        $categories = [
            'material' => '🧱 Materiales',
            'tool' => '🔧 Herramientas',
            'consumable' => '📦 Consumibles',
            'service' => '👷 Servicios',
            'other' => '📋 Otros',
        ];

        $grouped = $project->expenses->groupBy('category');

        foreach ($grouped as $category => $expenses) {
            $categoryLabel = $categories[$category] ?? ucfirst((string) $category);
            $categoryTotal = $expenses->sum('total_price');

            $budget .= "## {$categoryLabel}\n\n";
            $budget .= "| Estado | Material | Cantidad | P. Unit. | Total |\n";
            $budget .= "|:------:|----------|----------|----------|------:|\n";

            foreach ($expenses as $expense) {
                $status = $expense->is_purchased ? '✅' : '⬜';
                $name = $expense->name;
                if ($expense->supplier) {
                    $name .= " ({$expense->supplier})";
                }
                $qty = $expense->quantity.($expense->unit ? ' '.$expense->unit : '');
                $unitPrice = number_format((float) $expense->unit_price, 2, ',', '.').' €';
                $total = number_format((float) $expense->total_price, 2, ',', '.').' €';

                $budget .= "| {$status} | {$name} | {$qty} | {$unitPrice} | {$total} |\n";
            }

            $budget .= '| | | | **Subtotal** | **'.number_format((float) $categoryTotal, 2, ',', '.')." €** |\n\n";
        }

        $budget .= "---\n\n";
        $budget .= '_Exportado el '.now()->format('d/m/Y H:i')."_\n";

        return $budget;
    }
}

<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\CalendarEvent;
use App\Models\Project;

final class ProjectObserver
{
    public function saved(Project $project): void
    {
        $this->syncCalendarEvents($project);
    }

    public function deleted(Project $project): void
    {
        // Eliminar todos los eventos de calendario relacionados con este proyecto
        CalendarEvent::where('project_id', $project->id)
            ->whereIn('type', [
                CalendarEvent::TYPE_PROJECT_START,
                CalendarEvent::TYPE_PROJECT_DUE,
                CalendarEvent::TYPE_PROJECT_COMPLETED,
            ])
            ->delete();
    }

    private function syncCalendarEvents(Project $project): void
    {
        $this->syncStartedAt($project);
        $this->syncDueDate($project);
        $this->syncCompletedAt($project);
    }

    private function syncStartedAt(Project $project): void
    {
        $existingEvent = CalendarEvent::where('project_id', $project->id)
            ->where('type', CalendarEvent::TYPE_PROJECT_START)
            ->first();

        if ($project->started_at) {
            CalendarEvent::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'type' => CalendarEvent::TYPE_PROJECT_START,
                ],
                [
                    'user_id' => $project->user_id,
                    'title' => '🚀 Inicio: '.$project->title,
                    'description' => 'Fecha de inicio del proyecto',
                    'event_date' => $project->started_at,
                    'is_all_day' => true,
                    'color' => '#22c55e', // green
                ]
            );
        } elseif ($existingEvent) {
            $existingEvent->delete();
        }
    }

    private function syncDueDate(Project $project): void
    {
        $existingEvent = CalendarEvent::where('project_id', $project->id)
            ->where('type', CalendarEvent::TYPE_PROJECT_DUE)
            ->first();

        if ($project->due_date) {
            CalendarEvent::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'type' => CalendarEvent::TYPE_PROJECT_DUE,
                ],
                [
                    'user_id' => $project->user_id,
                    'title' => '📅 Fecha límite: '.$project->title,
                    'description' => 'Fecha límite del proyecto',
                    'event_date' => $project->due_date,
                    'is_all_day' => true,
                    'color' => '#f59e0b', // amber
                    'reminder_enabled' => true,
                    'reminder_minutes_before' => 1440, // 1 día antes
                ]
            );
        } elseif ($existingEvent) {
            $existingEvent->delete();
        }
    }

    private function syncCompletedAt(Project $project): void
    {
        $existingEvent = CalendarEvent::where('project_id', $project->id)
            ->where('type', CalendarEvent::TYPE_PROJECT_COMPLETED)
            ->first();

        if ($project->completed_at) {
            CalendarEvent::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'type' => CalendarEvent::TYPE_PROJECT_COMPLETED,
                ],
                [
                    'user_id' => $project->user_id,
                    'title' => '✅ Completado: '.$project->title,
                    'description' => 'Proyecto finalizado',
                    'event_date' => $project->completed_at,
                    'is_all_day' => true,
                    'color' => '#3b82f6', // blue
                ]
            );
        } elseif ($existingEvent) {
            $existingEvent->delete();
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Person;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoCalendarEventsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->error('No hay usuarios. Ejecuta primero AdminUserSeeder.');

            return;
        }

        $projects = Project::where('user_id', $user->id)->pluck('id', 'slug');
        $people = Person::where('user_id', $user->id)->get()->keyBy('name');

        $events = [
            [
                'title' => 'Entregar estantería a María',
                'description' => 'Llevar la estantería terminada a casa de María.',
                'type' => 'deadline',
                'event_date' => now()->addDays(14),
                'is_all_day' => true,
                'project_slug' => 'estanteria-flotante-de-madera-de-roble',
                'person_name' => 'María García',
                'color' => '#EF4444',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 1440, // 1 día antes
            ],
            [
                'title' => 'Cumpleaños de Ana',
                'description' => 'No olvidar la caja de regalo de kirigami.',
                'type' => 'birthday',
                'event_date' => now()->addDays(30),
                'is_all_day' => true,
                'person_name' => 'Ana Martínez',
                'color' => '#EC4899',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 10080, // 7 días antes
            ],
            [
                'title' => 'Comprar filamento PLA negro',
                'description' => 'Se está acabando el filamento negro. Pedir antes de que termine.',
                'type' => 'reminder',
                'event_date' => now()->addDays(3),
                'is_all_day' => false,
                'event_time' => '10:00',
                'color' => '#3B82F6',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 60,
            ],
            [
                'title' => 'Visitar taller de Pedro',
                'description' => 'Ver las nuevas herramientas que ha comprado y pedir consejos para la caja de herramientas.',
                'type' => 'custom',
                'event_date' => now()->addDays(7),
                'event_time' => '17:00',
                'is_all_day' => false,
                'person_name' => 'Pedro López',
                'project_slug' => 'caja-de-herramientas-portatil',
                'color' => '#10B981',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 120,
            ],
            [
                'title' => 'Reunión con Carlos',
                'description' => 'Hablar sobre imprimir piezas colaborativamente.',
                'type' => 'custom',
                'event_date' => now()->addDays(5),
                'event_time' => '19:00',
                'is_all_day' => false,
                'person_name' => 'Carlos Rodríguez',
                'color' => '#8B5CF6',
                'reminder_enabled' => false,
            ],
            [
                'title' => 'Deadline organizador modular',
                'description' => 'Terminar todos los módulos del organizador.',
                'type' => 'deadline',
                'event_date' => now()->addDays(10),
                'is_all_day' => true,
                'project_slug' => 'organizador-de-escritorio-modular',
                'color' => '#F59E0B',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 2880, // 2 días antes
            ],
            [
                'title' => 'Cumpleaños de María',
                'type' => 'birthday',
                'event_date' => now()->addDays(11), // Simula próximo cumpleaños
                'is_all_day' => true,
                'person_name' => 'María García',
                'color' => '#EC4899',
                'reminder_enabled' => true,
                'reminder_minutes_before' => 10080,
            ],
        ];

        $count = 0;
        foreach ($events as $eventDTO) {
            $projectId = null;
            if (! empty($eventDTO['project_slug']) && isset($projects[$eventDTO['project_slug']])) {
                $projectId = $projects[$eventDTO['project_slug']];
            }

            $personId = null;
            if (! empty($eventDTO['person_name']) && isset($people[$eventDTO['person_name']])) {
                $personId = $people[$eventDTO['person_name']]->id;
            }

            CalendarEvent::firstOrCreate(
                [
                    'title' => $eventDTO['title'],
                    'user_id' => $user->id,
                    'event_date' => $eventDTO['event_date'],
                ],
                [
                    'title' => $eventDTO['title'],
                    'description' => $eventDTO['description'] ?? null,
                    'type' => $eventDTO['type'],
                    'event_date' => $eventDTO['event_date'],
                    'event_time' => $eventDTO['event_time'] ?? null,
                    'is_all_day' => $eventDTO['is_all_day'],
                    'is_recurring' => false,
                    'color' => $eventDTO['color'] ?? null,
                    'reminder_enabled' => $eventDTO['reminder_enabled'] ?? false,
                    'reminder_minutes_before' => $eventDTO['reminder_minutes_before'] ?? null,
                    'project_id' => $projectId,
                    'person_id' => $personId,
                    'user_id' => $user->id,
                ]
            );
            $count++;
        }

        $this->command->info('✓ '.$count.' eventos de calendario de demo creados.');
    }
}

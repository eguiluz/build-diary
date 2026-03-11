<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoPeopleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->error('No hay usuarios. Ejecuta primero AdminUserSeeder.');

            return;
        }

        $people = [
            [
                'name' => 'María García',
                'email' => 'maria.garcia@example.com',
                'phone' => '+34 612 345 678',
                'birthday' => '1990-03-15',
                'birthday_reminder' => true,
                'reminder_days_before' => 7,
                'notes' => 'Amiga del colegio. Le gusta la carpintería.',
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'phone' => '+34 623 456 789',
                'birthday' => '1985-07-22',
                'birthday_reminder' => true,
                'reminder_days_before' => 3,
                'notes' => 'Vecino. Tiene una impresora 3D Prusa.',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@example.com',
                'phone' => '+34 634 567 890',
                'birthday' => '1992-11-08',
                'birthday_reminder' => false,
                'reminder_days_before' => 7,
                'notes' => 'Compañera de trabajo. Interesada en manualidades.',
            ],
            [
                'name' => 'Pedro López',
                'email' => 'pedro.lopez@example.com',
                'phone' => '+34 645 678 901',
                'birthday' => '1988-01-30',
                'birthday_reminder' => true,
                'reminder_days_before' => 5,
                'notes' => 'Primo. Tiene taller de carpintería profesional.',
            ],
            [
                'name' => 'Laura Sánchez',
                'email' => 'laura.sanchez@example.com',
                'phone' => '+34 656 789 012',
                'birthday' => '1995-09-12',
                'birthday_reminder' => true,
                'reminder_days_before' => 7,
                'notes' => 'Artista de papel. Hace origami profesional.',
            ],
        ];

        foreach ($people as $personDTO) {
            Person::firstOrCreate(
                ['email' => $personDTO['email'], 'user_id' => $user->id],
                array_merge($personDTO, ['user_id' => $user->id])
            );
        }

        $this->command->info('✓ '.count($people).' personas de demo creadas.');
    }
}

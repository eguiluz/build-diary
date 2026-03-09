<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoTagsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->error('No hay usuarios. Ejecuta primero AdminUserSeeder.');

            return;
        }

        $tags = [
            ['name' => 'Urgente', 'color' => '#EF4444'],
            ['name' => 'En espera', 'color' => '#F59E0B'],
            ['name' => 'Regalo', 'color' => '#EC4899'],
            ['name' => 'Para vender', 'color' => '#10B981'],
            ['name' => 'Aprendizaje', 'color' => '#3B82F6'],
            ['name' => 'Decoración', 'color' => '#8B5CF6'],
            ['name' => 'Reparación', 'color' => '#6366F1'],
            ['name' => 'Prototipo', 'color' => '#14B8A6'],
            ['name' => 'Encargo', 'color' => '#F97316'],
            ['name' => 'Personal', 'color' => '#06B6D4'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagData['name']), 'user_id' => $user->id],
                [
                    'name' => $tagData['name'],
                    'slug' => Str::slug($tagData['name']),
                    'color' => $tagData['color'],
                    'user_id' => $user->id,
                ]
            );
        }

        $this->command->info('✓ '.count($tags).' etiquetas de demo creadas.');
    }
}

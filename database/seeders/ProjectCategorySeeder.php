<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Carpintería',
                'slug' => 'carpentry',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'color' => 'amber',
                'order' => 1,
            ],
            [
                'name' => 'Impresión 3D',
                'slug' => '3d-printing',
                'icon' => 'heroicon-o-cube',
                'color' => 'blue',
                'order' => 2,
            ],
            [
                'name' => 'Arte en papel',
                'slug' => 'paper-art',
                'icon' => 'heroicon-o-document',
                'color' => 'pink',
                'order' => 3,
            ],
            [
                'name' => 'Electrónica',
                'slug' => 'electronics',
                'icon' => 'heroicon-o-cpu-chip',
                'color' => 'green',
                'order' => 4,
            ],
            [
                'name' => 'Costura',
                'slug' => 'sewing',
                'icon' => 'heroicon-o-scissors',
                'color' => 'purple',
                'order' => 5,
            ],
            [
                'name' => 'Manualidades',
                'slug' => 'crafts',
                'icon' => 'heroicon-o-paint-brush',
                'color' => 'orange',
                'order' => 6,
            ],
            [
                'name' => 'Otro',
                'slug' => 'other',
                'icon' => 'heroicon-o-ellipsis-horizontal-circle',
                'color' => 'gray',
                'order' => 99,
            ],
        ];

        foreach ($categories as $category) {
            ProjectCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}

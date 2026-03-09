<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProjectStatus>
 */
final class ProjectStatusFactory extends Factory
{
    protected $model = ProjectStatus::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Idea', 'Planificación', 'En progreso', 'En pausa', 'Completado', 'Cancelado',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => $this->faker->hexColor(),
            'order' => $this->faker->numberBetween(0, 10),
            'is_default' => false,
            'is_completed' => $name === 'Completado',
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
        ]);
    }
}

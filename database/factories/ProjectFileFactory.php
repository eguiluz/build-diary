<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectFile>
 */
final class ProjectFileFactory extends Factory
{
    protected $model = ProjectFile::class;

    public function definition(): array
    {
        $mimeType = $this->faker->randomElement(['image/jpeg', 'image/png', 'application/pdf']);
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
            default => 'bin',
        };

        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->uuid().'.'.$extension,
            'original_name' => $this->faker->words(3, true).'.'.$extension,
            'path' => 'projects/'.$this->faker->uuid().'.'.$extension,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'size' => $this->faker->numberBetween(1024, 5242880),
            'type' => ProjectFile::detectType($mimeType, $extension),
            'description' => $this->faker->optional()->sentence(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
        ]);
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'image/jpeg',
            'type' => ProjectFile::TYPE_IMAGE,
        ]);
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => 'application/pdf',
            'type' => ProjectFile::TYPE_PDF,
        ]);
    }
}

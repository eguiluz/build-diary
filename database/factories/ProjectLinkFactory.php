<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectLink>
 */
final class ProjectLinkFactory extends Factory
{
    protected $model = ProjectLink::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(3),
            'url' => $this->faker->url(),
            'type' => $this->faker->randomElement(array_keys(ProjectLink::getTypes())),
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

    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}

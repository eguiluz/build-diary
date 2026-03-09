<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DiaryEntry;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DiaryEntry>
 */
final class DiaryEntryFactory extends Factory
{
    protected $model = DiaryEntry::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->optional()->sentence(4),
            'content' => $this->faker->paragraphs(2, true),
            'type' => $this->faker->randomElement(array_keys(DiaryEntry::getTypes())),
            'entry_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'entry_time' => $this->faker->optional()->time('H:i'),
            'time_spent_minutes' => $this->faker->optional()->numberBetween(15, 480),
            'metadata' => null,
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

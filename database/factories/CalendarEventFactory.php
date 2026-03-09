<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarEvent>
 */
final class CalendarEventFactory extends Factory
{
    protected $model = CalendarEvent::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_id' => null,
            'person_id' => null,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'type' => $this->faker->randomElement(array_keys(CalendarEvent::getTypes())),
            'event_date' => $this->faker->dateTimeBetween('-1 month', '+3 months'),
            'event_time' => $this->faker->optional()->time('H:i'),
            'end_date' => null,
            'is_all_day' => $this->faker->boolean(70),
            'is_recurring' => false,
            'recurrence_rule' => null,
            'color' => $this->faker->optional()->hexColor(),
            'reminder_enabled' => $this->faker->boolean(30),
            'reminder_minutes_before' => $this->faker->randomElement([15, 30, 60, 1440]),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function ofType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}

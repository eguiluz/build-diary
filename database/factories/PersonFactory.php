<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
final class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->optional()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'birthday' => $this->faker->optional()->dateTimeBetween('-80 years', '-10 years'),
            'birthday_reminder' => $this->faker->boolean(30),
            'reminder_days_before' => $this->faker->randomElement([1, 3, 7, 14, 30]),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withBirthday(): static
    {
        return $this->state(fn (array $attributes) => [
            'birthday' => $this->faker->dateTimeBetween('-80 years', '-10 years'),
            'birthday_reminder' => true,
        ]);
    }
}

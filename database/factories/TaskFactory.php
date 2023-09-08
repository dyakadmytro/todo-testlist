<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Test task',
            'description' => $this->faker->text(50),
            'status' => $this->faker->randomElement(['todo', 'done']),
            'priority' => $this->faker->randomElement([1,2,3,4,5]),
        ];
    }
}

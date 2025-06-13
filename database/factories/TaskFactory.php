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
            'title' => $this->faker->sentence,
            'assignee' => $this->faker->name,
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(5),
            'time_tracked' => rand(0, 8),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\User;
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
            "title" => $this->faker->sentence(),
            "description" => $this->faker->paragraph(),
            "status_id" => Status::inRandomOrder()->first()->id,
            "created_by" => User::inRandomOrder()->first()->id,
            "assigned_to" => User::inRandomOrder()->first()->id,
            "finished_at" => $this->faker->boolean(30) ? $this->faker->dateTimeBetween("-1 month", "now") : null,
            "priority" => $this->faker->randomElement(["regular", "important", "urgent", "immediate"]),
        ];
    }
}

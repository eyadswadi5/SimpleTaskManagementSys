<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Factory::create();

        Task::factory()->count(10)->create()->each(function ($task) use ($faker) {
            Comment::factory()->count($faker->numberBetween(1, 10))
                ->create([
                    "task_id" => $task->id,
                    "user_id" => User::inRandomOrder()->where("isAdmin", 1)->first()->id
                ]);
        });
    }
}

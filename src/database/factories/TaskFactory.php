<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'created_by' => User::all()->random()->id,
            'title' => $this->faker->name(),
            'description' => $this->faker->realText(),
            'status' => $this->faker->randomElement(array_keys(Task::STATUSES)),
        ];
    }
}

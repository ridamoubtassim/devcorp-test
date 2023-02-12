<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->has(
            Project::factory()->count(1)->has(
                Task::factory()->count(5)->has(
                    UserTask::factory()->count(2), 'users'
                )
            )
        )->create();
    }
}

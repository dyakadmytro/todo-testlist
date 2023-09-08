<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task = Task::factory()->state([
            'user_id' => User::where('email', 'admin@example.com')->first()->id,
            'task_list_id' => TaskList::first()->id
        ])->create();
        Task::factory(2)->state([
            'user_id' => User::where('email', 'admin@example.com')->first()->id,
            'parent' => $task->id,
            'task_list_id' => TaskList::first()->id
        ])->create();
    }
}

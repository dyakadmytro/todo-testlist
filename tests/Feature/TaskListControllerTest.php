<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TaskList;

class TaskListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create a user and some task lists
        $user = User::factory()->create();
        $taskList1 = TaskList::factory()->create(['user_id' => $user->id, 'title' => 'List 1']);
        $taskList2 = TaskList::factory()->create(['user_id' => $user->id, 'title' => 'List 2']);

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the index action
        $response = $this->get('/api/user/task-list');

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the task lists
        $response->assertJson([$taskList1->toArray(), $taskList2->toArray()]);
    }

    public function testStore()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Send a POST request to the store action with task list data
        $response = $this->post('/api/user/task-list', [
            'title' => 'New Task List',
        ]);

        // Assert that the response status is 201 (created)
        $response->assertStatus(201);

        // Assert that the task list has been created in the database
        $this->assertDatabaseHas('task_lists', [
            'title' => 'New Task List',
            'user_id' => $user->id,
        ]);
    }

    public function testShow()
    {
        // Create a user and a task list
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id, 'title' => 'Task list test']);

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the show action with the task list ID
        $response = $this->get("/api/user/task-list/{$taskList->id}");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the task list data
        $response->assertJson($taskList->toArray());
    }

    public function testUpdate()
    {
        // Create a user and a task list
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id, 'title' => 'Task list test']);

        // Authenticate the user
        $this->actingAs($user);

        // Send a PUT request to the update action with updated task list data
        $response = $this->put("/api/user/task-list/{$taskList->id}", [
            'title' => 'Updated Task List Name',
        ]);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the task list has been updated in the database
        $this->assertDatabaseHas('task_lists', [
            'id' => $taskList->id,
            'title' => 'Updated Task List Name',
        ]);
    }

    public function testDestroy()
    {
        // Create a user and a task list
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id, 'title' => 'Task list test']);

        // Authenticate the user
        $this->actingAs($user);

        // Send a DELETE request to the destroy action with the task list ID
        $response = $this->delete("/api/user/task-list/{$taskList->id}");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the task list has been deleted from the database
        $this->assertDatabaseMissing('task_lists', ['id' => $taskList->id]);
    }
}

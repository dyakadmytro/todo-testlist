<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create a user and tasks
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $tasks = Task::factory(3)->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the index action
        $response = $this->get('/api/tasks');

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the user's tasks
        $response->assertJson($tasks->toArray());
    }

    public function testListTasks()
    {
        // Create a user and a task list with tasks
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $tasks = Task::factory(3)->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the listTasks action
        $response = $this->get("/api/task-list/{$taskList->id}/tasks");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the tasks in the task list
        $response->assertJson($tasks->toArray());
    }

    public function testStoreTask()
    {
        // Create a user and a task list
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a POST request to the storeTask action with task data
        $response = $this->post("/api/task-list/{$taskList->id}/tasks", [
            'name' => 'New Task',
            'parent' => $task->id,
            'description' => 'Task description',
            'status' => 'todo',
            'priority' => 3,
        ]);

        // Assert that the response status is 201 (created)
        $response->assertStatus(201);

        // Assert that the task has been created in the database
        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task',
            'parent' => $task->id,
            'user_id' => $user->id,
            'task_list_id' => $taskList->id,
            'description' => 'Task description',
            'status' => 'todo',
            'priority' => 3,
        ]);
    }

    public function testShow()
    {
        // Create a user and a task
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the show action with the task ID
        $response = $this->get("/api/tasks/{$task->id}");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the task data
        $response->assertJson($task->toArray());
    }

    public function testUpdate()
    {
        // Create a user and a task
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a PUT request to the update action with updated task data
        $response = $this->put("/api/tasks/{$task->id}", [
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'status' => 'done',
            'priority' => 5,
        ]);

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the task has been updated in the database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'status' => 'done',
            'priority' => 5,
        ]);
    }

    public function testDestroy()
    {
        // Create a user and a task
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'task_list_id' => $taskList->id]);

        // Authenticate the user
        $this->actingAs($user);

        // Send a DELETE request to the destroy action with the task ID
        $response = $this->delete("/api/tasks/{$task->id}");

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the task has been deleted from the database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testAccessDenied()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $taskList = TaskList::factory()->create(['user_id' => $user1->id]);

        // Create a task owned by user1
        $task = Task::factory()->create(['user_id' => $user1->id, 'task_list_id' => $taskList->id]);

        // Authenticate user2
        $this->actingAs($user2);

        // Attempt to access the task owned by user1
        $response = $this->get("/api/tasks/{$task->id}");

        // Assert that the response status is 403 (Forbidden)
        $response->assertStatus(403);
    }
}


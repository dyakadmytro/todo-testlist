<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected User $user;

    public function __construct()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->user->tasks);
    }

    public function listTasks(TaskList $task_list)
    {
        return response()->json($task_list->tasks);
    }

    public function storeTask(StoreTaskRequest $request, TaskList $task_list)
    {
        Task::create(array_merge($request->all(), ['user_id' => $this->user->id, 'task_list_id' => $task_list->id]));
        return response('Task created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        return response('Task updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response('Task deleted', 200);
    }
}

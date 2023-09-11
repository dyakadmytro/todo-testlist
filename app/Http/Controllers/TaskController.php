<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckTaskRequest;
use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Services\TaskFilterService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetTasksRequest $request)
    {
        $query = Auth::user()->tasks()->getQuery();
        TaskFilterService::build($request, $query);
        return response()->json($query->get());
    }

    public function listTasks(GetTasksRequest $request, TaskList $task_list)
    {
        $query = $task_list->tasks()->getQuery();
        TaskFilterService::build($request, $query);
        return response()->json($query->get());
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create(array_merge($request->only(['parent', 'title', 'description', 'task_list_id', 'priority']), ['user_id' => Auth::user()->id]));
        return response('Task created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->only(['parent', 'title', 'description', 'task_list_id', 'priority']));
        return response('Task updated', 200);
    }

    public function checkTask(CheckTaskRequest $request, Task $task)
    {
        $task->update(['status' => 'done']);
        return response('The task set done', 200);
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

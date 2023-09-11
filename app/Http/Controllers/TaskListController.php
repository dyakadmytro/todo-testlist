<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTasklistRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTasklistRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TaskList::class, 'taskList');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Auth::user()->taskLists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasklistRequest $request)
    {
        TaskList::create(array_merge($request->only(['title']), ['user_id' => Auth::user()->id]));
        return response('TaskList created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $taskList)
    {
        return response()->json($taskList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTasklistRequest $request, TaskList $taskList)
    {
        $taskList->update($request->only(['title']));
        return response('TaskList updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList)
    {
        $taskList->delete();
        return response('TaskList deleted', 200);
    }
}

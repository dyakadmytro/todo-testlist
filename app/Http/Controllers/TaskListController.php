<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTasklistRequest;
use App\Http\Requests\UpdateTasklistRequest;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskListController extends Controller
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
        return response()->json($this->user->taskLists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTasklistRequest $request)
    {
        TaskList::create(array_merge($request->all(), ['user_id' => $this->user->id]));
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
        $taskList->update($request->all());
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

<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $task->user->id === $user->id;
    }

    public function createTask(User $user, TaskList $taskList): bool
    {
        return $taskList->user->id === $user->id;
    }

    public function allowToDone(User $user, Task $task): bool
    {
        return $task->childs->every(function ($item) {
            return $item->status === 'done';
        });
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $task->user->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $task->user->id === $user->id;
    }
}

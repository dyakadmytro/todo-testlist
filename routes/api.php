<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\TaskListController;
use \App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user/login', [AuthController::class, 'login'])->name('login');

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('tasks', TaskController::class)->except(['create', 'edit']);
    Route::resource('task-list', TaskListController::class)->except(['create', 'edit']);
    Route::get('task-list/{task_list}/tasks', [TaskController::class, 'listTasks']);
    Route::patch('tasks/{task}/check', [TaskController::class, 'checkTask']);
});


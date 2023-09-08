<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\TaskListController;

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
    Route::get('data', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('logout', function (Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('task-list', TaskListController::class)->except(['create', 'edit']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Temporarily remove the closure middleware to fix artisan error
Route::middleware(['auth:sanctum', 'is.admin'])
    ->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
    });

// Task management routes
Route::middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::put('tasks/{id}', [TaskController::class, 'update']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
});

// User can view their tasks and update status
Route::middleware('auth:sanctum')->group(function () {
    Route::get('my-tasks', [TaskController::class, 'index']); // user sees only their tasks
    Route::patch('tasks/{id}/status', [TaskController::class, 'updateStatus']);
}); 
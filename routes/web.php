<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::get('/', [TaskController::class, 'index'])->name('home')->middleware('auth.check');
Route::get('/tasks', [TaskController::class, 'getAllTasks'])->name('tasks.index')->middleware('auth.check');

Route::get('/profile', function () {
    return view('profile');
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/check-auth', [AuthController::class, 'checkAuth'])->name('check.auth');

// API routes for AJAX operations - Protected
Route::middleware('auth.check')->group(function () {
    Route::get('/api/tasks', [TaskController::class, 'getTasksJson'])->name('tasks.api');
    Route::get('/tasks/stats', [TaskController::class, 'getStats'])->name('tasks.stats');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{id}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
});

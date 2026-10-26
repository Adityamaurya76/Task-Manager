<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::resource('tasks', TaskController::class);
    });

    // Employee routes
    Route::middleware('role:employee')->group(function () {
        Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my-tasks');
        Route::get('/my-tasks/{task}', [TaskController::class, 'show'])->name('my-tasks.show');
        Route::patch('/my-tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('my-tasks.status');
    });
});

require __DIR__.'/auth.php';
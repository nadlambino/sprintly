<?php

use App\Http\Controllers\Api\v1\StatusController;
use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('/tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::put('/tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::get('/tasks/parents', [TaskController::class, 'parents'])->name('tasks.parents');

    Route::apiResource('/statuses', StatusController::class)->only(['index', 'store', 'update', 'destroy']);
});

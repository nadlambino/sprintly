<?php

use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
   Route::post('/tasks/store', [TaskController::class, 'store'])->name('api.tasks.store');
   Route::post('/tasks/draft', [TaskController::class, 'draft'])->name('api.tasks.draft');
});

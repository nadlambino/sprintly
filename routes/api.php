<?php

use App\Http\Controllers\Api\v1\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::name('api')->apiResource('/tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
});

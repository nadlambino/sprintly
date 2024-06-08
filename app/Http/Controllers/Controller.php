<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class Controller
{
    public function success(?string $message = null, array|Collection|Model $data = [], array $metadata = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'metadata' => $metadata
        ], $status);
    }

    public function error(?string $message = null, array|Collection $errors = [], int $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}

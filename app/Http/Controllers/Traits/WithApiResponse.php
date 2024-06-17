<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

trait WithApiResponse
{
    /**
     * A method that returns a JSON response with optional message, data, and metadata.
     *
     * @param ?string $message The message to be included in the response.
     * @param array|Collection|Model|EloquentCollection $data The data to be included in the response.
     * @param array $metadata Additional metadata to be included in the response.
     * @param int $status The HTTP status code for the response.
     * @return JsonResponse The JSON response containing message, data, and metadata.
     */
    public function success(?string $message = null, array|Collection|Model|EloquentCollection $data = [], array $metadata = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'metadata' => $metadata
        ], $status);
    }

    /**
     * Returns a JSON response with an error message and optional errors array.
     *
     * @param string|null $message The error message to include in the response.
     * @param array|Collection $errors An optional array of errors to include in the response.
     * @param int $status The HTTP status code for the response. Default is 500.
     * @return JsonResponse The JSON response with the error message and optional errors array.
     */
    public function error(?string $message = null, array|Collection $errors = [], int $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}

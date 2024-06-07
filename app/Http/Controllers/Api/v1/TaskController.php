<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $task = $request->user()->tasks()->create($request->validated());

            return $this->success('Task created successfully', $task, 201);
        } catch (Exception) {
            return $this->error('Task could not be created. Please try again later.');
        }
    }

    public function draft(CreateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['published_at'] = null;

            $task = $request->user()->tasks()->create($data);

            return $this->success('Task drafted successfully', $task, 201);
        } catch (Exception) {
            return $this->error('Task could not be drafted. Please try again later.');
        }
    }
}

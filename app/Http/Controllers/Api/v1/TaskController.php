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

            return $this->success('Task was successfully created.', $task, 201);
        } catch (Exception) {
            return $this->error('Something went wrong while creating the task. Please try again later.');
        }
    }

    public function draft(CreateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['published_at'] = null;

            $task = $request->user()->tasks()->create($data);

            return $this->success('Task was saved to draft.', $task, 201);
        } catch (Exception) {
            return $this->error('Something went wrong while drafting the task. Please try again later.');
        }
    }
}

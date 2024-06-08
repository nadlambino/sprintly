<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateRequest;
use App\QueryBuilders\Tasks\Filters\DraftFilter;
use App\QueryBuilders\Tasks\Filters\StatusFilter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $tasks = QueryBuilder::for($request->user()->tasks())
                ->allowedFilters([
                    'title',
                    'content',
                    AllowedFilter::custom('status', new StatusFilter),
                    AllowedFilter::custom('draft', new DraftFilter)
                ])
                ->allowedIncludes(['status', 'images'])
                ->allowedSorts(['title', 'created_at'])
                ->get();

            return $this->success('Tasks retrieved successfully.', $tasks);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

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

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Tasks\CreateRequest;
use App\Http\Requests\Api\Tasks\UpdateRequest;
use App\Models\Task;
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
                ->defaultSort('created_at')
                ->allowedSorts(['title', 'created_at'])
                ->paginate($request->get('per_page', 10));

            return $this->success('Tasks retrieved successfully.', $tasks->all());
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the tasks. Please try again later.');
        }
    }

    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $publish = filter_var($request->get('publish', false), FILTER_VALIDATE_BOOL);
            $data['published_at'] = $publish ? now() : null;

            $task = $request->user()->tasks()->create($data);

            return $this->success('Task was successfully created.', $task, 201);
        } catch (Exception) {
            return $this->error('Something went wrong while creating the task. Please try again later.');
        }
    }

    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        try {
            $data = $request->validated();
            $publish = filter_var($request->get('publish', false), FILTER_VALIDATE_BOOL);
            $data['published_at'] = $publish ? now() : null;

            $task->update($data);

            return $this->success('Task was successfully updated.', $task);
        } catch (Exception) {
            return $this->error('Something went wrong while updating the task. Please try again later.');
        }
    }
}

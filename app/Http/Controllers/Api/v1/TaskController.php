<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\Http\Requests\Api\Tasks\CreateRequest;
use App\Http\Requests\Api\Tasks\UpdateRequest;
use App\Models\Status;
use App\Models\Task;
use App\QueryBuilders\Task\Filters\ExceptFilter;
use App\QueryBuilders\Task\Filters\PublishedFilter;
use App\QueryBuilders\Task\Filters\StatusFilter;
use App\QueryBuilders\Filters\TrashedFilter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    use WithApiResponse;

    /**
     * Get list of tasks.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $tasks = QueryBuilder::for($request->user()->tasks())
                ->allowedFilters([
                    'title',
                    AllowedFilter::exact('parent_id'),
                    AllowedFilter::exact('status_id'),
                    AllowedFilter::custom('status', new StatusFilter),
                    AllowedFilter::custom('published', new PublishedFilter),
                    AllowedFilter::custom('trashed', new TrashedFilter)
                ])
                ->allowedIncludes(['status', 'images', 'parent', 'children'])
                ->defaultSort('created_at')
                ->allowedSorts(['title', 'created_at'])
                ->whereHas('status')
                ->paginate($request->get('per_page', 10));

            return $this->success(
                'Tasks retrieved successfully.',
                $tasks->all(),
                [
                    'has_next_page' => $tasks->hasMorePages(),
                    'next_page' => $tasks->hasMorePages() ? intval($request->get('page', 1)) + 1 : null,
                    'total' => $tasks->total()
                ]
            );
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the tasks. Please try again later.');
        }
    }

    /**
     * Get list of tasks that are valid to be a parent.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function parents(Request $request): JsonResponse
    {
        try {
            $tasks = QueryBuilder::for($request->user()->tasks()->published())
                ->allowedFilters([
                    'title',
                    AllowedFilter::custom('except', new ExceptFilter),
                ])
                ->paginate($request->get('per_page', 10));

            return $this->success('Tasks retrieved successfully.', $tasks->all());
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the tasks. Please try again later.');
        }
    }

    /**
     * Store new task.
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $publish = boolval($request->get('publish', false));
            $data['published_at'] = $publish ? now() : null;

            $task = $request->user()->tasks()->create($data);

            return $this->success('Task was successfully created.', $task, status: 201);
        } catch (Exception $exception) {
            return $this->error('Something went wrong while creating the task. Please try again later.'. $exception->getMessage());
        }
    }

    /**
     * Update task.
     *
     * @param UpdateRequest $request
     * @param Task $task
     * @param Status $status
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        try {
            $data = array_filter($request->validated());
            $published = boolval($request->get('publish', false));

            $data['published_at'] = match(true) {
                ! $request->has('publish') => $task->published_at,
                $request->has('publish') && $published => now(),
                $request->has('publish') && ! $published => null,
            };

            Task::deletePreviousUploads(boolval($request->get('replace_images', false)));

            $task->update($data);

            return $this->success('Task was successfully updated.', $task);
        } catch (Exception) {
            return $this->error('Something went wrong while updating the task. Please try again later.');
        }
    }

    /**
     * Delete task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            Gate::authorize('delete', $task);

            $task->delete();

            return $this->success('Task was successfully deleted.');
        } catch (Exception) {
            return $this->error('Something went wrong while deleting the task. Please try again later.');
        }
    }

    /**
     * Restore task.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $task = Task::onlyTrashed()->findOrFail($id);

            Gate::authorize('restore', $task);

            $task->restore();

            return $this->success('Task was successfully restored.');
        } catch (Exception) {
            return $this->error('Something went wrong while restoring the task. Please try again later.');
        }
    }
}

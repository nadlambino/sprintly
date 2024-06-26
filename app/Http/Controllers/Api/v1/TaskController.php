<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\Http\Requests\Api\Tasks\CreateRequest;
use App\Http\Requests\Api\Tasks\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Status;
use App\Models\Task;
use App\QueryBuilders\Task\TaskBuilder;
use App\Services\Task\ReportService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        $tasks = TaskBuilder::make()
            ->of($request->user())
            ->build()
            ->selectRaw('tasks.*, (TIMESTAMPDIFF(MINUTE, tasks.started_at, tasks.ended_at) / 60) as time_spent')
            ->whereHas('status')
            ->paginate($request->get('per_page', 10));

        return $this->success(
            'Tasks retrieved successfully.',
            TaskResource::collection($tasks->all()),
            [
                'has_next_page' => $tasks->hasMorePages(),
                'next_page' => $tasks->hasMorePages() ? intval($request->get('page', 1)) + 1 : null,
                'total' => $tasks->total()
            ]
        );
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
            $tasks = TaskBuilder::make()
                ->of($request->user())
                ->build()
                ->published()
                ->paginate($request->get('per_page', 10));

            return $this->success('Tasks retrieved successfully.', $tasks->all());
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the tasks. Please try again later.');
        }
    }

    public function metrics(Request $request)
    {
        $reports = new ReportService($request->user());

        return $this->success('Tasks metrics successfully retrieved.', [
            'total' => $reports->getTotalPublished(),
            'metrics' => $reports->getTotalPerStatus(),
            'total_hours_spent_this_week' => $reports->getTotalHoursSpentThisWeek(),
            'total_hours_spent_last_week' => $reports->getTotalHoursSpentLastWeek(),
            'average_hours_spent_this_week' => $reports->getAverageHoursSpentThisWeek(),
            'average_hours_spent_last_week' => $reports->getAverageHoursSpentLastWeek(),
            'speed_comparison' => $reports->getSpeedSummary(),
        ]);
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
            $publish = filter_var($request->get('publish', false), FILTER_VALIDATE_BOOL);
            $data['published_at'] = $publish ? now() : null;

            $task = $request->user()->tasks()->create($data);

            return $this->success('Task was successfully created.', $task, status: 201);
        } catch (Exception) {
            return $this->error('Something went wrong while creating the task. Please try again later.');
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
            $published = filter_var($request->get('publish'), FILTER_VALIDATE_BOOL);

            $data['published_at'] = match(true) {
                $request->has('publish') && $published && ! $task->published_at => now(),
                $request->has('publish') && ! $published && $task->published_at => null,
                default                                                         => $task->published_at,
            };

            Task::deletePreviousUploads(filter_var($request->get('replace_images', false), FILTER_VALIDATE_BOOL));

            $task->update($data);

            return $this->success('Task was successfully updated.', $task);
        } catch (Exception) {
            return $this->error('Something went wrong while updating the task. Please try again later.');
        }
    }

    /**
     * Update task status.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function progress(Task $task): JsonResponse
    {
        try {
            Gate::authorize('update', $task);

            $nextStatus = $task->status->next()->first();

            if (! $nextStatus) {
                return $this->success('Task is already on its final status.', $task);
            }

            $task->update(['status_id' => $nextStatus->id]);

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
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(int $id, Request $request): JsonResponse
    {
        try {
            $task = TaskBuilder::make()
                ->of($request->user())
                ->filters(['id' => $id])
                ->build()
                ->onlyTrashed()
                ->firstOrFail();

            Gate::authorize('restore', $task);

            $task->restore();

            return $this->success('Task was successfully restored.');
        } catch (Exception) {
            return $this->error('Something went wrong while restoring the task. Please try again later.');
        }
    }
}

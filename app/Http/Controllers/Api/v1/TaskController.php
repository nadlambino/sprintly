<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\Http\Requests\Api\Tasks\CreateRequest;
use App\Http\Requests\Api\Tasks\UpdateRequest;
use App\Http\Resources\TaskReportResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\QueryBuilders\Task\TaskBuilder;
use App\Services\Task\ReportService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    use WithApiResponse;

    public function index(Request $request): ResourceCollection
    {
        try {
            $tasks = TaskBuilder::make()
                ->of($request->user())
                ->build()
                ->selectRaw('tasks.*, (TIMESTAMPDIFF(MINUTE, tasks.started_at, tasks.ended_at) / 60) as time_spent')
                ->whereHas('status')
                ->paginate($request->get('per_page', 10));

            return TaskResource::collection($tasks->all())
                ->additional([
                    'message' => 'Tasks retrieved successfully.',
                    'metadata' => [
                        'has_next_page' => $tasks->hasMorePages(),
                        'next_page' => $tasks->hasMorePages() ? intval($request->get('page', 1)) + 1 : null,
                        'total' => $tasks->total()
                    ]
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while retrieving tasks', errors: [$exception->getMessage()]);
        }
    }

    public function parents(Request $request): ResourceCollection
    {
        try {
            $tasks = TaskBuilder::make()
                ->of($request->user())
                ->build()
                ->published()
                ->paginate($request->get('per_page', 10));

            return TaskResource::collection($tasks->all())
                ->additional([
                    'message' => 'Parent tasks retrieved successfully.',
                    'metadata' => [
                        'has_next_page' => $tasks->hasMorePages(),
                        'next_page' => $tasks->hasMorePages() ? intval($request->get('page', 1)) + 1 : null,
                        'total' => $tasks->total()
                    ]
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while retrieving parent tasks', errors: [$exception->getMessage()]);
        }
    }

    public function metrics(Request $request): TaskReportResource
    {
        try {
            $reports = new ReportService($request->user());

            return (new TaskReportResource([
                'total' => $reports->getTotalPublished(),
                'metrics' => $reports->getTotalPerStatus(),
                'total_hours_spent_this_week' => $reports->getTotalHoursSpentThisWeek(),
                'total_hours_spent_last_week' => $reports->getTotalHoursSpentLastWeek(),
                'average_hours_spent_this_week' => $reports->getAverageHoursSpentThisWeek(),
                'average_hours_spent_last_week' => $reports->getAverageHoursSpentLastWeek(),
                'speed_comparison' => $reports->getSpeedSummary()
            ]))->additional([
                'message' => 'Task reports retrieved successfully.',
            ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while retrieving reports', errors: [$exception->getMessage()]);
        }
    }

    public function store(CreateRequest $request): TaskResource
    {
        try {
            $task = $request->user()->tasks()->create($request->validated());

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task was successfully created.',
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while creating task', errors: [$exception->getMessage()]);
        }
    }

    public function update(UpdateRequest $request, Task $task): TaskResource
    {
        try {
            Task::deletePreviousUploads(filter_var($request->get('replace_images', false), FILTER_VALIDATE_BOOL));

            $task->update($request->validated());

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task was successfully updated.',
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while updating task', errors: [$exception->getMessage()]);
        }
    }

    public function progress(Task $task): TaskResource
    {
        try {
            Gate::authorize('update', $task);

            $nextStatus = $task->status->next()->first();

            if (! $nextStatus) {
                return (new TaskResource($task))
                    ->additional([
                        'message' => 'Task is already at the last status.',
                    ]);
            }

            $task->update(['status_id' => $nextStatus->id]);

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task was successfully progressed.',
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while progressing task', errors: [$exception->getMessage()]);
        }
    }

    public function destroy(Task $task): TaskResource
    {
        try {
            Gate::authorize('delete', $task);

            $task->delete();

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task was successfully deleted.',
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while deleting task', errors: [$exception->getMessage()]);
        }
    }

    public function restore(int $id, Request $request): TaskResource
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

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task was successfully restored.',
                ]);
        } catch (Exception $exception) {
            throw new ApiException(message: 'Error while restoring task', errors: [$exception->getMessage()]);
        }
    }
}

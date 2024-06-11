<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\TaskService;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $service = new TaskService($task);

        $service->updateParent();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $service = new TaskService($task);

        $service->updateParent();
        $service->updateChildren();
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        $service = new TaskService($task);

        $service->deleteChildren();
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        $service = new TaskService($task);

        $service->restore();
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        $service = new TaskService($task);

        $service->forceDeleteChildren();
    }
}

<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if (! $task->isDirty('status_id')) {
            return;
        }

        $task = $task->fresh();

        if (strtolower($task->status->name) === 'in progress' && ! $task->started_at) {
            $task->started_at = now();
        }

        // If there is a next status and the task is already ended, it end date should be set to null
        else if ($task->status->next()->exists() && $task->ended_at) {
            $task->ended_at = null;
        }

        // If there is no next status, it means that it is the final status so it should be ended
        else if (! $task->status->next()->exists()) {
            $task->started_at = $task->started_at ?: now();
            $task->ended_at = now();
        }

        $task->saveQuietly();
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}

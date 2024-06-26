<?php

namespace App\Services\Task\Transitions;

use App\Models\Task;
use Closure;

class Completed
{
    public function handle(Task $task, Closure $next)
    {
        if (! $task->status->next()->exists()) {
            $task->started_at = $task->started_at ?: now();
            $task->ended_at = now();

            return $task;
        }

        return $next($task);
    }
}

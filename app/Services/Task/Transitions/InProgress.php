<?php

namespace App\Services\Task\Transitions;

use App\Models\Task;
use Closure;

class InProgress
{
    public function handle(Task $task, Closure $next)
    {
        if (strtolower($task->status->name) === 'in progress' && ! $task->started_at) {
            $task->started_at = now();

            return $task;
        }

        return $next($task);
    }
}

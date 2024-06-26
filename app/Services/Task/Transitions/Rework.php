<?php

namespace App\Services\Task\Transitions;

use App\Models\Task;
use Closure;

class Rework
{
    public function handle(Task $task, Closure $next)
    {
        if ($task->ended_at && $task->status->next()->exists()) {
            $task->ended_at = null;

            return $task;
        }

        return $next($task);
    }
}

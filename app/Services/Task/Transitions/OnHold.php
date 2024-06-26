<?php

namespace App\Services\Task\Transitions;

use App\Models\Task;
use App\QueryBuilders\Status\StatusBuilder;
use Closure;

class OnHold
{
    public function handle(Task $task, Closure $next)
    {
        $inprogress = StatusBuilder::make()
            ->of($task->user)
            ->filters(['name' => 'In Progress'])
            ->build()
            ->first();

        if ($inprogress && $task->status->order < $inprogress->order && $task->started_at) {
            $task->started_at = null;

            return $task;
        }

        return $next($task);
    }
}

<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Services\Task\Transitions\Completed;
use App\Services\Task\Transitions\InProgress;
use App\Services\Task\Transitions\OnHold;
use App\Services\Task\Transitions\Rework;
use Illuminate\Support\Facades\Pipeline;

class TransitionService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected Task $task) { }

    public function transition()
    {
        Pipeline::send($this->task)
            ->through([
                OnHold::class,
                InProgress::class,
                Rework::class,
                Completed::class,
            ])
            ->thenReturn()
            ->saveQuietly();
    }
}

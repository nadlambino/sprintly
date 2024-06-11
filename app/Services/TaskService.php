<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public const TODO = 1;
    public const IN_PROGRESS = 2;
    public const DONE = 3;

    public function __construct(private Task $task)
    {

    }

    public function updateParent()
    {
        if ($this->task->isDirty('status_id') === false || $this->task->parent === null) {
            return;
        }

        if ($this->isTaskMovingFromDoneToPrevious() && $this->task->parent->status_id === self::DONE) {
            $this->task->parent->update(['status_id' => $this->task->status_id]);
        }

        if ($this->isTaskMovingToDone() && $this->task->siblings()->notDone()->count() === 0) {
            $this->task->parent->update(['status_id' => $this->task->status_id]);
        }
    }

    public function updateChildren()
    {
        if ($this->task->isDirty('status_id') === false) {
            return;
        }

        if ($this->isTaskMovingFromDoneToPrevious()) {
            $this->task->children()->done()->get()->each(fn (Task $task) => $task->update(['status_id' => $this->task->status_id]));
        }

        if ($this->isTaskMovingToDone()) {
            $this->task->children()->notDone()->get()->each(fn (Task $task) => $task->update(['status_id' => $this->task->status_id]));
        }
    }

    private function isTaskMovingFromDoneToPrevious(): bool
    {
        return $this->task->isDirty('status_id')
            && $this->task->getOriginal('status_id') === self::DONE;
    }

    private function isTaskMovingToDone(): bool
    {
        return $this->task->isDirty('status_id')
            && $this->task->status_id === self::DONE;
    }

    public function restore()
    {
        if ($this->task->parent_id === null) {
            return;
        }

        // If it has parent_id but parent is null (probably deleted), then set its parent to null when restored.
        if ($this->task->parent === null) {
            $this->task->update(['parent_id' => null]);
        }
    }

    public function deleteChildren(): void
    {
        $this->task->children()->get()->each(fn (Task $task) => $task->delete());
    }

    public function forceDeleteChildren(): void
    {
        $this->task->children()->get()->each(fn (Task $task) => $task->forceDelete());
    }
}

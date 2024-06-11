<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    use WithStatusChecks;

    public const TODO = 1;
    public const IN_PROGRESS = 2;
    public const DONE = 3;
    private bool $parentWasUpdated = false;

    public function __construct(private Task $task) { }

    public function updateParent()
    {
        if (! $this->didStatusChanged() || ! $this->hasParent() || $this->isTaskWasUnpublished()) {
            return;
        }

        $this->updateParentStatusToWhen($this->task->status_id, $this->isTaskWasRecentlyCreatedAndNotSetToDone())
            ->updateParentStatusToWhen($this->task->status_id, $this->isTaskWasPublishedAndIsNotDone())
            ->updateParentStatusToWhen($this->task->status_id, $this->isTaskMovingFromDoneToPrevious() && $this->isParentDone())
            ->updateParentStatusToWhen(self::DONE,  ($areAllSiblingsDone = $this->areAllSiblingsDone()) && $this->isTaskMovingFromDoneToPrevious() && $this->isParentDone())
            ->updateParentStatusToWhen(self::DONE, $this->isTaskMovingToDone() && $areAllSiblingsDone);
    }

    public function updateChildren()
    {
        if (! $this->didStatusChanged()) {
            return;
        }

        if ($this->isTaskMovingToDone()) {
            $this->task->children()->notDone()->get()->each(fn (Task $task) => $task->update(['status_id' => $this->task->status_id]));
        }
    }

    public function restore()
    {
        if (! $this->hasParent()) {
            return;
        }

        $this->updateParentStatusToWhen($this->task->status_id, $this->isTaskNotDone() && $this->isParentDone());

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

    private function updateParentStatusToWhen(int $status, bool $when): self
    {
        if ($this->parentWasUpdated || ! $when) {
            return $this;
        }

        $this->task->parent->update(['status_id' => $status]);
        $this->parentWasUpdated = true;

        return $this;
    }
}

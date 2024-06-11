<?php

namespace App\Services;

trait WithStatusChecks
{
    protected function isTaskNotDone(): bool
    {
        return $this->task->status_id !== TaskService::DONE;
    }

    protected function didStatusChanged(): bool
    {
        return $this->task->isDirty('status_id');
    }

    protected function hasParent(): bool
    {
        return $this->task->parent_id !== null && $this->task->parent;
    }

    protected function hasParentButDeleted(): bool
    {
        return $this->task->parent_id && ! $this->task->parent;
    }

    protected function isTaskWasRecentlyCreatedAndNotSetToDone(): bool
    {
        return $this->task->wasRecentlyCreated && $this->task->status_id !== TaskService::DONE;
    }

    protected function isParentDone(): bool
    {
        return $this->task->parent->status_id === TaskService::DONE;
    }

    protected function isTaskWasUnpublished(): bool
    {
        return ! $this->task->wasRecentlyCreated
            && $this->task->isDirty('publish_at')
            && $this->task->getOriginal('publish_at') !== null
            && $this->task->published_at === null;
    }

    protected function isTaskWasPublishedAndIsNotDone(): bool
    {
        return $this->task->isDirty('publish_at')
            && $this->task->getOriginal('publish_at') === null
            && $this->task->published_at !== null
            && $this->task->status_id !== TaskService::DONE;
    }

    protected function isTaskMovingFromDoneToPrevious(): bool
    {
        return $this->task->isDirty('status_id')
            && $this->task->getOriginal('status_id') === TaskService::DONE;
    }

    protected function isTaskMovingToDone(): bool
    {
        return $this->task->isDirty('status_id')
            && $this->task->status_id === TaskService::DONE;
    }

    protected function areAllSiblingsDone(): bool
    {
        return $this->task->siblings()->notDone()->count() === 0;
    }
}

<?php

namespace App\Policies;

use App\Models\PriorityLevel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PriorityLevelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PriorityLevel $priorityLevel): Response
    {
        return auth()->check() && $user->id === $priorityLevel->user_id ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PriorityLevel $priorityLevel): Response
    {
        return auth()->check() && $user->id === $priorityLevel->user_id ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PriorityLevel $priorityLevel): Response
    {
        return auth()->check() && $user->id === $priorityLevel->user_id ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PriorityLevel $priorityLevel): Response
    {
        return auth()->check() && $user->id === $priorityLevel->user_id ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PriorityLevel $priorityLevel): Response
    {
        return auth()->check() && $user->id === $priorityLevel->user_id ? Response::allow() : Response::denyAsNotFound();
    }
}

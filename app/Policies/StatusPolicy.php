<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
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
    public function view(User $user, Status $status): Response
    {
        // Deny as not found when the user doesn't own the status
        // This is to imply that the status is not existing rather than being not accessible.
        return auth()->check() && $user->id === $status->user_id ? Response::allow() : Response::denyAsNotFound();
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
    public function update(User $user, Status $status): Response
    {
        // Deny as not found when the user doesn't own the status
        // This is to imply that the status is not existing rather than being not accessible.
        return auth()->check() && $user->id === $status->user_id && ! $status->is_default ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Status $status): Response
    {
        // Deny as not found when the user doesn't own the status
        // This is to imply that the status is not existing rather than being not accessible.
        return auth()->check() && $user->id === $status->user_id && ! $status->is_default ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Status $status): Response
    {
        // Deny as not found when the user doesn't own the status
        // This is to imply that the status is not existing rather than being not accessible.
        return auth()->check() && $user->id === $status->user_id && ! $status->is_default ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Status $status): Response
    {
        // Deny as not found when the user doesn't own the status
        // This is to imply that the status is not existing rather than being not accessible.
        return auth()->check() && $user->id === $status->user_id && ! $status->is_default ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function sort(User $user, Status $status): bool
    {
        return auth()->check() && $user->id === $status->user_id;
    }
}

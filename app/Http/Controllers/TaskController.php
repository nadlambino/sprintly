<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    private Collection $statuses;

    public function __construct()
    {
        $this->statuses = request()->user()->statuses->sortBy('order');
    }

    /**
     * Render index page.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Tasks/Index', [
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Render show page.
     *
     * @param Task $task
     * @return Response
     */
    public function show(Task $task): Response
    {
        Gate::authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => $task->load(['status', 'images', 'parent', 'parent.status', 'parent.images']),
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Render create page.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Tasks/Form', [
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Render edit page.
     *
     * @param Task $task
     * @return Response
     */
    public function edit(Task $task): Response
    {
        Gate::authorize('view', $task);

        return Inertia::render('Tasks/Form', [
            'statuses' => $this->statuses,
            'task' => $task->load(['status', 'images', 'parent']),
        ]);
    }

    /**
     * Render drafts page.
     *
     * @return Response
     */
    public function drafts(): Response
    {
        return Inertia::render('Tasks/Drafts', [
            'statuses' => $this->statuses,
        ]);
    }

    /**
     * Render trashed page.
     *
     * @return Response
     */
    public function trashed(): Response
    {
        return Inertia::render('Tasks/Trashed', [
            'statuses' => $this->statuses,
            'days_before_deletion' => config('app.delete_trash_days_old'),
        ]);
    }
}

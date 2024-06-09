<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Index', [
            'statuses' => Status::all(['id', 'name']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Form', [
            'statuses' => Status::all(['id', 'name']),
        ]);
    }

    public function edit(Task $task)
    {
        Gate::authorize('view', $task);

        return Inertia::render('Tasks/Form', [
            'statuses' => Status::all(['id', 'name']),
            'task' => $task->load(['status', 'images']),
        ]);
    }

    public function drafts()
    {
        return Inertia::render('Tasks/Drafts', [
            'statuses' => Status::all(['id', 'name']),
        ]);
    }

    public function trashed()
    {
        return Inertia::render('Tasks/Trashed', [
            'statuses' => Status::all(['id', 'name']),
            'days_before_deletion' => config('app.delete_trash_days_old'),
        ]);
    }
}

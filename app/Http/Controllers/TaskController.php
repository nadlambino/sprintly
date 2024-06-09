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
        return Inertia::render('Tasks/Index');
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
        return Inertia::render('Tasks/Drafts');
    }
}

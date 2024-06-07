<?php

namespace App\Http\Controllers;

use App\Models\Status;
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
}

<?php

namespace App\Http\Controllers;

use App\Models\PriorityLevel;
use Inertia\Inertia;

class PriorityLevelController extends Controller
{
    public function index()
    {
        return Inertia::render('PriorityLevel/Index');
    }

    public function create()
    {
        return Inertia::render('PriorityLevel/Form');
    }

    public function edit(PriorityLevel $priorityLevel)
    {
        return Inertia::render('PriorityLevel/Form', [
            'priorityLevel' => $priorityLevel
        ]);
    }
}

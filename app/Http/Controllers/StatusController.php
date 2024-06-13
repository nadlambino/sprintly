<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Inertia\Inertia;

class StatusController extends Controller
{
    public function index()
    {
        return Inertia::render('Statuses/Index');
    }

    public function create()
    {
        return Inertia::render('Statuses/Form');
    }

    public function edit(Status $status)
    {
        return Inertia::render('Statuses/Form', ['status' => $status]);
    }

    public function trashed()
    {
        return Inertia::render('Statuses/Trashed');
    }
}

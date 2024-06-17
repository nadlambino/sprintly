<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Inertia\Inertia;

class StatusController extends Controller
{
    public function index()
    {
        return Inertia::render('Status/Index');
    }

    public function create()
    {
        return Inertia::render('Status/Form');
    }

    public function edit(Status $status)
    {
        return Inertia::render('Status/Form', ['status' => $status]);
    }

    public function trashed()
    {
        return Inertia::render('Status/Trashed');
    }
}

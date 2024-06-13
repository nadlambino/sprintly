<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class StatusController extends Controller
{
    public function index()
    {
        return Inertia::render('Statuses/Index');
    }

    public function trashed()
    {
        return Inertia::render('Statuses/Trashed');
    }
}

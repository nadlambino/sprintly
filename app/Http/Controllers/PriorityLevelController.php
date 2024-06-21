<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PriorityLevelController extends Controller
{
    public function index()
    {
        return Inertia::render('PriorityLevel/Index');
    }
}

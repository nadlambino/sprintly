<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        return $this->success('Successfully retrieved statuses.', $request->user()->statuses);
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\QueryBuilders\PriorityLevel\PriorityLevelBuilder;
use Illuminate\Http\Request;

class PriorityLevelController extends Controller
{
    use WithApiResponse;

    public function index(Request $request)
    {
        $priorityLevels = PriorityLevelBuilder::make()
            ->of($request->user())
            ->build()
            ->get();

        return $this->success('Statuses retrieved successfully.', $priorityLevels);
    }
}

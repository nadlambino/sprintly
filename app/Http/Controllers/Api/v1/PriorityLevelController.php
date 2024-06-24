<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\Http\Requests\Api\PriorityLevel\CreateRequest;
use App\Http\Requests\PriorityLevel\UpdateRequest;
use App\Models\PriorityLevel;
use App\QueryBuilders\PriorityLevel\PriorityLevelBuilder;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PriorityLevelController extends Controller
{
    use WithApiResponse;

    public function index(Request $request)
    {
        $priorityLevels = PriorityLevelBuilder::make()
            ->of($request->user())
            ->build()
            ->get();

        return $this->success('Priority levels retrieved successfully.', $priorityLevels);
    }

    public function store(CreateRequest $request)
    {
        $priorityLevel = $request->user()->priorityLevels()->create($request->validated());

        return $this->success('Priority level created successfully.', $priorityLevel);
    }

    public function update(UpdateRequest $request, PriorityLevel $priorityLevel)
    {
        $priorityLevel->update($request->validated());

        return $this->success('Priority level updated successfully.', $priorityLevel);
    }

    public function destroy(PriorityLevel $priorityLevel)
    {
        try {
            Gate::allows('delete', $priorityLevel);

            $priorityLevel->delete();

            return $this->success('Priority level deleted successfully.');
        } catch (Exception $exception) {
            if ($exception instanceof QueryException) {
                return $this->error('This priority level cannot be deleted because it is in use.');
            }

            return $this->error('Something went wrong while deleting the priority level. Please try again later.');
        }
    }
}

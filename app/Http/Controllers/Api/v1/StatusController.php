<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\WithApiResponse;
use App\Http\Requests\Api\Status\CreateRequest;
use App\Http\Requests\Api\Status\SortRequest;
use App\Http\Requests\Api\Status\UpdateRequest;
use App\Models\Status;
use App\QueryBuilders\Status\StatusBuilder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StatusController extends Controller
{
    use WithApiResponse;

    public function index(Request $request)
    {
        $statuses = StatusBuilder::make()
            ->of($request->user())
            ->build()
            ->get();

        return $this->success('Statuses retrieved successfully.', $statuses);
    }

    public function store(CreateRequest $request)
    {
        try {
            if ($request->user()->statuses->count() >= config('app.max_status_per_user')) {
                return $this->error('You have reached the maximum number of statuses allowed.');
            }

            $status = $request->user()->statuses()->create($request->validated());

            return $this->success('Status was successfully created.', $status);
        } catch (Exception) {
            return $this->error('Something went wrong while creating the status. Please try again later.');
        }
    }

    public function update(UpdateRequest $request, Status $status)
    {
        try {
            Gate::authorize('update', $status);

            $status->update($request->validated());

            return $this->success('Status was successfully updated.', $status);
        } catch (Exception) {
            return $this->error('Something went wrong while updating the status. Please try again later.');
        }
    }

    public function sort(SortRequest $request, Status $status)
    {
        $newOrder = $request->validated('new_order');
        $oldOrder = $status->order;

        if ($oldOrder < $newOrder) {
            StatusBuilder::make()
                ->of($request->user())
                ->filters(['order_between' => [$oldOrder, $newOrder]])
                ->build()
                ->decrement('order');
        } else {
            StatusBuilder::make()
                ->of($request->user())
                ->filters(['order_between' => [$newOrder, $oldOrder]])
                ->build()
                ->increment('order');
        }

        $status->order = $newOrder;
        $status->save();

        return $this->success('Statuses were successfully sorted.', $status);
    }

    public function destroy(Status $status)
    {
        try {
            Gate::authorize('delete', $status);

            $status->delete();

            return $this->success('Status was successfully deleted.');
        } catch (Exception) {
            return $this->error('Something went wrong while deleting the status. Please try again later.');
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $status = Status::onlyTrashed()->findOrFail($id);

            Gate::authorize('forceDelete', $status);

            $status->forceDelete();

            return $this->success('Status was successfully deleted permanently.');
        } catch (Exception) {
            return $this->error('Something went wrong while deleting the status permanently. Please try again later.');
        }
    }

    public function restore(int $id)
    {
        try {
            $status = Status::onlyTrashed()->findOrFail($id);

            Gate::authorize('restore', $status);

            $status->restore();

            return $this->success('Status was successfully restored.');
        } catch (Exception) {
            return $this->error('Something went wrong while restoring the status. Please try again later.');
        }
    }
}

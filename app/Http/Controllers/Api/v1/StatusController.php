<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\QueryBuilders\Filters\TrashedFilter;
use App\QueryBuilders\Status\Filters\SearchFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        try {
            $statuses = QueryBuilder::for($request->user()->statuses())
                ->allowedFilters([
                    AllowedFilter::custom('search', new SearchFilter),
                    AllowedFilter::custom('trashed', new TrashedFilter),
                ])
                ->defaultSort('order')
                ->allowedSorts(['order', 'created_at', 'name'])
                ->get();

            return $this->success('Statuses retrieved successfully.', $statuses);
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the statuses. Please try again later.');
        }
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

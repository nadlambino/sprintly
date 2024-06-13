<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\QueryBuilders\Status\Filters\SearchFilter;
use Exception;
use Illuminate\Http\Request;
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
                ])
                ->defaultSort('order')
                ->allowedSorts(['order', 'created_at', 'name'])
                ->get();

            return $this->success('Statuses retrieved successfully.', $statuses);
        } catch (Exception) {
            return $this->error('Something went wrong while retrieving the statuses. Please try again later.');
        }
    }
}

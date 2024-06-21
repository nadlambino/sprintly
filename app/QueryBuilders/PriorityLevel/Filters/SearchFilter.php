<?php

namespace App\QueryBuilders\PriorityLevel\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property)
    {
        $query->search($value);
    }
}

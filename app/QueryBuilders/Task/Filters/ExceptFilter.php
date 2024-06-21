<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

class ExceptFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where('id', '!=', $value);
    }
}

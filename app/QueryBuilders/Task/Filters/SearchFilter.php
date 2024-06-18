<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->search($value);
    }
}

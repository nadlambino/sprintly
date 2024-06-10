<?php

namespace App\QueryBuilders\Tasks\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ExceptFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where('id', '!=', $value);
    }
}

<?php

namespace App\QueryBuilders\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TrashedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->when(
            filter_var($value, FILTER_VALIDATE_BOOL),
            fn ($query) => $query->onlyTrashed(),
            fn ($query) => $query->withoutTrashed()
        );
    }
}

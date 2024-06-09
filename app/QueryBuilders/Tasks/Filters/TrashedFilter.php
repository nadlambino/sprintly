<?php

namespace App\QueryBuilders\Tasks\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TrashedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->when(
            $value,
            fn ($query) => $query->onlyTrashed(),
            fn ($query) => $query->withoutTrashed()
        );
    }
}

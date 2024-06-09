<?php

namespace App\QueryBuilders\Tasks\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class StatusFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->when(
            ! empty($value),
            fn ($query) => $query->whereHas('status', fn($query) => $query->where('name', $value)),
        );
    }
}

<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class StatusFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->when(
            empty($value) || strtolower($value) === 'all',
            fn ($query) => $query,
            fn ($query) => $query->whereHas('status', fn($query) => $query->where('name', $value)),
        );
    }
}

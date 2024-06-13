<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PublishedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->when(
            $value,
            fn ($query) => $query->whereNotNull('published_at'),
            fn ($query) => $query->whereNull('published_at')
        );
    }
}

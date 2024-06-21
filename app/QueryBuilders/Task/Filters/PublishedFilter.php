<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

class PublishedFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->when(
            filter_var($value, FILTER_VALIDATE_BOOL),
            fn ($query) => $query->published(),
            fn ($query) => $query->drafts()
        );
    }
}

<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PublishedFilter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->when(
            boolval($value),
            fn ($query) => $query->published(),
            fn ($query) => $query->drafts()
        );
    }
}

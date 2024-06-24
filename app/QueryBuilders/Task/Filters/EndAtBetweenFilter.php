<?php

namespace App\QueryBuilders\Task\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

class EndAtBetweenFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (! is_array($value) || (is_array($value) && count($value) !== 2)) {
            throw new \InvalidArgumentException('The `end_at_between` value must be an array of two dates.');
        }

        $query->when(! empty($value), fn ($query) => $query->whereBetween('ended_at', $value));
    }
}

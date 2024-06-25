<?php

namespace App\QueryBuilders\Status\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

class OrderBetweenFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (! is_array($value) || (is_array($value) && count($value) !== 2)) {
            throw new \InvalidArgumentException('The `order_between` value must be an array of two numbers.');
        }

        $query->whereBetween('order', $value);
    }
}

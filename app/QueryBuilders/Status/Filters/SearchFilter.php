<?php

namespace App\QueryBuilders\Status\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('description', 'like', "%{$value}%")
            ->orWhere('color', 'like', "%{$value}%");
    }
}

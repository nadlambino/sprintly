<?php

namespace App\QueryBuilders\PriorityLevel;

use App\Models\PriorityLevel;
use App\QueryBuilders\Builder;
use App\QueryBuilders\PriorityLevel\Filters\SearchFilter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use NadLambino\QueryBuilder\AllowedFilter;
use NadLambino\QueryBuilder\QueryBuilder;

class PriorityLevelBuilder
{
    use Builder;

    public function of(Authenticatable|Model $owner): static
    {
        $this->builder = $owner->priorityLevels();

        return $this;
    }

    public function build(): QueryBuilder
    {
        $builder = isset($this->builder) ? $this->builder : PriorityLevel::query();

        return QueryBuilder::for($builder, $this->getSource())
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter),
            ])
            ->allowedIncludes(['tasks'])
            ->defaultSort('score')
            ->allowedSorts(['name', 'score']);
    }
}

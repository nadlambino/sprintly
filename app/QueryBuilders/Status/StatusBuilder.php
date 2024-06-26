<?php

namespace App\QueryBuilders\Status;

use App\Models\Status;
use App\QueryBuilders\Builder as BaseBuilder;
use App\QueryBuilders\Filters\TrashedFilter;
use App\QueryBuilders\Status\Filters\OrderBetweenFilter;
use App\QueryBuilders\Status\Filters\SearchFilter;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use NadLambino\QueryBuilder\AllowedFilter;
use NadLambino\QueryBuilder\QueryBuilder;

class StatusBuilder
{
    use BaseBuilder;

    public function of(Authenticatable|Model $owner): static
    {
        $this->builder = $owner->statuses();

        return $this;
    }

    public function build(): QueryBuilder
    {
        $builder = isset($this->builder) ? $this->builder : Status::query();

        return QueryBuilder::for($builder, $this->getSource())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('name'),
                AllowedFilter::custom('search', new SearchFilter),
                AllowedFilter::custom('trashed', new TrashedFilter),
                AllowedFilter::custom('order_between', new OrderBetweenFilter),
            ])
            ->defaultSort('order')
            ->allowedSorts(['order', 'created_at', 'name']);
    }
}

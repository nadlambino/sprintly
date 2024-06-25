<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

trait Builder
{
    protected Builder|Relation $builder;

    protected array $source = [];

    protected bool $notFromRequest = false;

    public function __construct(Request $request = null)
    {
        $this->source = $request?->all() ?? [];
    }

    public static function make(): static
    {
        return new static(request());
    }

    public function filters(array $filters): static
    {
        $this->source['filter'] = $filters;

        return $this;
    }

    public function includes(array $includes): static
    {
        $this->source['include'] = implode(',', $includes);

        return $this;
    }

    public function sort(string $sort): static
    {
        $this->source['sort'] = $sort;

        return $this;
    }

    public function notFromRequest(): static
    {
        $this->notFromRequest = true;
        $this->source = [];

        return $this;
    }

    public function mergeFilterToRequest(array $filters): static
    {
        $this->source['filter'] = array_merge(request('filter', []), $filters);

        return $this;
    }

    public function mergeIncludeToRequest(array $includes): static
    {
        $this->source['include'] = request('include', '') . ',' . implode(',', $includes);

        return $this;
    }


    /**
     * Get the source to be used in the query.
     *
     * @return array
     */
    protected function getSource(): array
    {
        return $this->notFromRequest ? $this->source : request()->all();
    }
}

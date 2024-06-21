<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait Builder
{
    protected Builder|Relation $builder;

    protected array $source;

    public static function make(): static
    {
        return new static();
    }

    public function filters(array $filters): static
    {
        $this->source['filter'] = $filters;

        return $this;
    }

    public function includes(array $includes): static
    {
        $this->source['include'] = $includes;

        return $this;
    }

    public function sort(string $sort): static
    {
        $this->source['sort'] = $sort;

        return $this;
    }

    public function source(array|Collection $source): static
    {
        $this->source = is_array($source) ? $source : $source->toArray();

        return $this;
    }

    /**
     * Get the source to be used in the query.
     * We return null so that it will use the request object as the source.
     *
     * @return array|null
     */
    protected function getSource(): ?array
    {
        return isset($this->source) ? $this->source : null;
    }
}

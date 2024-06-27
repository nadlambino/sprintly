<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait StatusScopes
{
    /**
     * Get next status based on order.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNext(Builder $query): Builder
    {
        return $query->where('order', '>', $this->order)
            ->where('user_id', $this->user_id)
            ->orderBy('order', 'asc')
            ->limit(1);
    }

    public function scopePrevious(Builder $query): Builder
    {
        return $query->where('order', '<', $this->order)
            ->where('user_id', $this->user_id)
            ->orderBy('order', 'desc')
            ->limit(1);
    }

    public function scopeSearch(Builder $query, mixed $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('color', 'like', "%{$search}%");
        });
    }
}

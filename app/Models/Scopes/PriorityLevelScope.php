<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait PriorityLevelScope
{
    public function scopeSearch(Builder $query, mixed $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
}

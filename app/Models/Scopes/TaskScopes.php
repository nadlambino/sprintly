<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait TaskScopes
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeDrafts(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    public function scopeTrashed(Builder $query): Builder
    {
        return $query->whereNotNull('deleted_at');
    }

    public function scopeSearch(Builder $query, mixed $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }
}

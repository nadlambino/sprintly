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
}

<?php

namespace App\Models\Shared;

use Illuminate\Support\Carbon;

trait WithDeletedSince
{
    /**
     * Boots the "deleted_since" attribute for the model.
     *
     * This function is called when the model is retrieved and adds the "deleted_since"
     * attribute to the model's appends property. This allows the "deleted_since" attribute
     * to be included when the model is serialized.
     *
     * @return void
     */
    public static function bootWithDeletedSince(): void
    {
        static::retrieved(function ($model) {
            $model->appends[] = 'deleted_since';
        });
    }

    /**
     * Get the "deleted_since" attribute for the model.
     *
     * @return string|null
     */
    public function getDeletedSinceAttribute(): ?string
    {
        return $this->deleted_at
            ? Carbon::parse($this->deleted_at)->diffForHumans()
            : null;
    }
}

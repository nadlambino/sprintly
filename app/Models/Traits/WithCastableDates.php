<?php

namespace App\Models\Traits;

trait WithCastableDates
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at'   => 'datetime:Y-m-d h:i A',
            'updated_at'   => 'datetime:Y-m-d h:i A',
            'deleted_at'   => 'datetime:Y-m-d h:i A',
            'published_at' => 'datetime:Y-m-d h:i A',
        ];
    }
}

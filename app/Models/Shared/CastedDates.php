<?php

namespace App\Models\Shared;

trait CastedDates
{
    protected const DATETIME_CAST_FORMAT = 'datetime:Y-m-d h:i A';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at'   => self::DATETIME_CAST_FORMAT,
            'updated_at'   => self::DATETIME_CAST_FORMAT,
            'deleted_at'   => self::DATETIME_CAST_FORMAT,
        ];
    }
}

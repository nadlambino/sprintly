<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function scopeWhereIdOrName(Builder $query, int|string|null $id, string|null $name)
    {
        return $query->where('id', $id)->orWhere('name', $name);
    }
}

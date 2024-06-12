<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'color',
        'description',
        'order',
    ];

    /**
     * Relation method between Status and Task.
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Scope where id or name.
     *
     * @param Builder $query
     * @param integer|string|null $id
     * @param string|null $name
     * @return Builder
     */
    public function scopeWhereIdOrName(Builder $query, int|string|null $id, string|null $name): Builder
    {
        return $query->where('id', $id)->orWhere('name', $name);
    }
}

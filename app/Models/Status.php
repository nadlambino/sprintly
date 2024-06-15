<?php

namespace App\Models;

use App\Models\Traits\WithCastableDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Status extends Model
{
    use HasFactory, SoftDeletes, WithCastableDates;

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
        'is_default',
    ];

    protected $appends = [
        'deleted_since',
    ];

    /**
     * Get the deleted_since attribute.
     *
     * @return string|null
     */
    public function getDeletedSinceAttribute(): ?string
    {
        return $this->deleted_at ? Carbon::parse($this->deleted_at)->diffForHumans() : null;
    }

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

<?php

namespace App\Models;

use App\Models\Scopes\StatusScopes;
use App\Models\Shared\CastedDates;
use App\Models\Shared\WithDeletedSince;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes, CastedDates, WithDeletedSince, StatusScopes;

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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($status) {
            $status->order = Status::where('user_id', $status->user_id)->max('order') + 1;
        });
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
}

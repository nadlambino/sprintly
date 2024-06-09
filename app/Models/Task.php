<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use NadLambino\Uploadable\Models\Traits\HasUpload;

class Task extends Model
{
    use HasFactory, SoftDeletes, HasUpload;

    protected $fillable = [
        'title',
        'content',
        'status_id',
        'published_at'
    ];

    protected $appends = [
        'deleted_since',
        'to_be_deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'created_at'   => 'datetime:Y-m-d h:i A',
            'updated_at'   => 'datetime:Y-m-d h:i A',
            'deleted_at'   => 'datetime:Y-m-d h:i A',
            'published_at' => 'datetime:Y-m-d h:i A',
        ];
    }

    public function getDeletedSinceAttribute(): ?string
    {
        return $this->deleted_at ? Carbon::parse($this->deleted_at)->diffForHumans() : null;
    }

    public function getToBeDeletedAtAttribute(): ?string
    {
        return $this->deleted_at
            ? Carbon::parse($this->deleted_at)->addDays(config('app.delete_trash_days_old', 30))->endOfDay()->diffForHumans()
            : null;
    }

    /**
     * Relation method between Task and Status
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Relation method between Task and User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

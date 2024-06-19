<?php

namespace App\Models;

use App\Models\Scopes\TaskScopes;
use App\Models\Shared\CastedDates;
use App\Models\Shared\WithDeletedSince;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use NadLambino\Uploadable\Models\Traits\HasUpload;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasFactory, SoftDeletes, HasUpload, TaskScopes, WithDeletedSince, CastedDates {
        casts as baseCasts;
    }

    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'status_id',
        'published_at'
    ];

    protected $appends = [
        'to_be_deleted_at',
        'is_progressible'
    ];

    protected function casts(): array
    {
        return [
            ...$this->baseCasts(),
            'published_at' => self::DATETIME_CAST_FORMAT,
            'started_at'   => self::DATETIME_CAST_FORMAT,
            'ended_at'     => self::DATETIME_CAST_FORMAT
        ];
    }

    /**
     * Get the to_be_deleted_at attribute.
     *
     * @return string|null
     */
    public function getToBeDeletedAtAttribute(): ?string
    {
        return $this->deleted_at
            ? Carbon::parse($this->deleted_at)->addDays(config('app.delete_trash_days_old', 30))->endOfDay()->diffForHumans()
            : null;
    }

    /**
     * Get the is_progressible attribute.
     *
     * @return bool
     */
    public function getIsProgressibleAttribute(): bool
    {
        return $this->status?->next()->exists() ?: false;
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

    /**
     * Relation method between child Task and parent Task
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Relation method between parent Task and child Task
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}

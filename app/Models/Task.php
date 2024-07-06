<?php

namespace App\Models;

use App\Models\Scopes\TaskScopes;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use NadLambino\Uploadable\Concerns\Uploadable;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use TaskScopes;
    use Uploadable;

    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'status_id',
        'published_at',
        'start_at',
        'due_at',
        'priority_level_id',
    ];

    protected $appends = [
        'can_move_forward',
        'can_move_backward',
    ];

    protected function casts(): array
    {
        return [
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
            'deleted_at'   => 'datetime',
            'published_at' => 'datetime',
            'start_at'     => 'datetime',
            'due_at'       => 'datetime',
            'started_at'   => 'datetime',
            'ended_at'     => 'datetime',
        ];
    }

    /**
     * Get the is_progressible attribute.
     *
     * @return bool
     */
    public function getCanMoveForwardAttribute(): bool
    {
        return $this->status?->next()->exists() ?: false;
    }

    public function getCanMoveBackwardAttribute(): bool
    {
        return $this->status?->previous()->exists() ?: false;
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

    public function priorityLevel(): BelongsTo
    {
        return $this->belongsTo(PriorityLevel::class);
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

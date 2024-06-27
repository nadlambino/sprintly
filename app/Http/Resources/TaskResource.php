<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),

            'created_at'        => $this->created_at->format('Y/m/d h:i A'),
            'updated_at'        => $this->updated_at->format('Y/m/d h:i A'),
            'published_at'      => $this->published_at?->format('Y/m/d h:i A'),
            'start_at'          => $this->start_at?->format('Y/m/d h:i A'),
            'due_at'            => $this->due_at?->format('Y/m/d h:i A'),
            'started_at'        => $this->started_at?->format('Y/m/d h:i A'),
            'ended_at'          => $this->ended_at?->format('Y/m/d h:i A'),
            'deleted_at'        => $this->deleted_at?->format('Y/m/d h:i A'),
            'to_be_deleted_at'  => $this->deleted_at?->addDays($this->getDeleteTrashDaysOldValue())->endOfDay()->format('Y/m/d h:i A'),
            'deleted_since'     => $this->deleted_at?->diffForHumans(),

            'parent'            => new TaskResource($this->whenLoaded('parent')),
            'children'          => TaskResource::collection($this->whenLoaded('children')),
            'status'            => $this->whenLoaded('status'),
            'priority_level'    => $this->whenLoaded('priorityLevel'),
            'images'            => $this->whenLoaded('images'),
        ];
    }

    protected function getDeleteTrashDaysOldValue(): int
    {
        return once(fn () => (int) config('app.delete_trash_days_old', 30));
    }
}

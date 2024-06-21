<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'start_at' => Carbon::parse($this->start_at)->format('Y-m-d h:i A'),
            'due_at'   => Carbon::parse($this->due_at)->format('Y-m-d h:i A'),
        ];
    }
}

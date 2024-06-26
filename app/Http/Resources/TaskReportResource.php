<?php

namespace App\Http\Resources;

use App\Services\Task\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskReportResource extends JsonResource
{
    /** @var ReportService */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => $this->resource->getTotalPublished(),
            'metrics' => $this->resource->getTotalPerStatus(),
            'total_hours_spent_this_week' => $this->resource->getTotalHoursSpentThisWeek(),
            'total_hours_spent_last_week' => $this->resource->getTotalHoursSpentLastWeek(),
            'average_hours_spent_this_week' => $this->resource->getAverageHoursSpentThisWeek(),
            'average_hours_spent_last_week' => $this->resource->getAverageHoursSpentLastWeek(),
            'speed_comparison' => $this->resource->getSpeedSummary()
        ];
    }
}

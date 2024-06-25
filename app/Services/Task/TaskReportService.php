<?php

namespace App\Services\Task;

use App\Models\User;
use App\QueryBuilders\Task\TaskBuilder;
use Carbon\Carbon;

final class TaskReportService
{
    protected Carbon $startOfWeek;
    protected Carbon $endOfWeek;

    public function __construct(protected User $user)
    {
        $this->startOfWeek = now()->startOfWeek()->startOfDay();
        $this->endOfWeek = now()->endOfWeek()->startOfDay();
    }

    public function getTotalPublished(): int
    {
        return once(fn () => TaskBuilder::make()
            ->of($this->user)
            ->filters([
                'published' => true
            ])
            ->build()
            ->whereHas('status')
            ->count()
        );
    }

    public function getTotalPerStatus(): array
    {
        return once(fn () => $this->user
            ->statuses()
            ->select('name', 'color')
            ->withCount(['tasks as count' => fn ($query) => $query->published()->whereHas('status')])
            ->orderBy('order')
            ->get()
            ->setHidden(['deleted_since'])
            ->toArray()
        );
    }

    public function getTotalHoursSpentThisWeek(): float
    {
        return once(fn () => TaskBuilder::make()
            ->of($this->user)
            ->filters([
                'published' => true,
                'ended_at_between' => [$this->startOfWeek, $this->endOfWeek]
            ])
            ->build()
            ->whereHas('status')
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0
        );
    }

    public function getTotalHoursSpentLastWeek(): float
    {
        return once(function() {
            $start = clone $this->startOfWeek;
            $end = clone $this->endOfWeek;

            return TaskBuilder::make()
                ->of($this->user)
                ->filters([
                    'published' => true,
                    'ended_at_between' => [$start->subWeek(), $end->subWeek()]
                ])
                ->build()
                ->whereHas('status')
                ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
                ->first()
                ->time_spent ?? 0;
        });
    }

    public function getAverageHoursSpentThisWeek(): float
    {
        return once(fn () => TaskBuilder::make()
            ->of($this->user)
            ->filters([
                'published' => true,
                'ended_at_between' => [$this->startOfWeek, $this->endOfWeek]
            ])
            ->build()
            ->whereHas('status')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0
        );
    }

    public function getAverageHoursSpentLastWeek(): float
    {
        return once(function () {
            $start = clone $this->startOfWeek;
            $end = clone $this->endOfWeek;

            return TaskBuilder::make()
                ->of($this->user)
                ->filters([
                    'published' => true,
                    'ended_at_between' => [$start->subWeek(), $end->subWeek()]
                ])
                ->build()
                ->whereHas('status')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
                ->first()
                ->time_spent ?? 0;
        });
    }

    public function getSpeedSummary(): string
    {
        $lastWeekHours = $this->getAverageHoursSpentLastWeek();
        $thisWeekHours = $this->getAverageHoursSpentThisWeek();

        if ($lastWeekHours == 0) {
            return "No data from last week to compare.";
        }

        $percentageChange = (($thisWeekHours - $lastWeekHours) / $lastWeekHours) * 100;
        $formattedPercentageChange = abs(number_format($percentageChange, 2));

        return match (true) {
            $percentageChange > 0 => "You are slower this week. Average time spent increased by " . $formattedPercentageChange . "%.",
            $percentageChange < 0 => "You are faster this week. Average time spent decreased by " . $formattedPercentageChange . "%.",
            default => "Your pace is unchanged this week. Average time spent is " . $formattedPercentageChange . "%.",
        };
    }
}

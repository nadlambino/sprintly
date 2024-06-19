<?php

namespace App\Services\Task;

use App\Models\User;
use Carbon\Carbon;

final class TaskReport
{
    protected Carbon $startOfWeek;
    protected Carbon $endOfWeek;
    protected float $totalHoursSpentThisWeek;
    protected float $totalHoursSpentLastWeek;
    protected float $averageHoursSpentThisWeek;
    protected float $averageHoursSpentLastWeek;

    public function __construct(protected User $user)
    {
        $this->startOfWeek = now()->startOfWeek()->startOfDay();
        $this->endOfWeek = now()->endOfWeek()->startOfDay();
    }

    public function getTotalPublished(): int
    {
        return $this->user
            ->tasks()
            ->published()
            ->whereHas('status')
            ->count();
    }

    public function getTotalPerStatus(): array
    {
        return $this->user
            ->statuses()
            ->select('name', 'color')
            ->withCount(['tasks as count' => fn ($query) => $query->published()->whereHas('status')])
            ->orderBy('order')
            ->get()
            ->setHidden(['deleted_since'])
            ->toArray();
    }

    public function getTotalHoursSpentThisWeek(): float
    {
        return $this->totalHoursSpentThisWeek ??= $this->user
            ->tasks()
            ->published()
            ->whereHas('status')
            ->whereBetween('ended_at', [$this->startOfWeek, $this->endOfWeek])
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0;
    }

    public function getTotalHoursSpentLastWeek(): float
    {
        $start = clone $this->startOfWeek;
        $end = clone $this->endOfWeek;

        return $this->totalHoursSpentLastWeek ??= $this->user
            ->tasks()
            ->published()
            ->whereHas('status')
            ->whereBetween('ended_at', [$start->subWeek(), $end->subWeek()])
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0;
    }

    public function getAverageHoursSpentThisWeek(): float
    {
        return $this->averageHoursSpentThisWeek ??= $this->user
            ->tasks()
            ->published()
            ->whereHas('status')
            ->whereBetween('ended_at', [$this->startOfWeek, $this->endOfWeek])
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0;
    }

    public function getAverageHoursSpentLastWeek(): float
    {
        $start = clone $this->startOfWeek;
        $end = clone $this->endOfWeek;

        return $this->averageHoursSpentLastWeek ??= $this->user
            ->tasks()
            ->published()
            ->whereHas('status')
            ->whereBetween('ended_at', [$start->subWeek(), $end->subWeek()])
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, ended_at)) as time_spent')
            ->first()
            ->time_spent ?? 0;
    }

    public function getSpeedComparison(): string
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
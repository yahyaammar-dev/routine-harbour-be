<?php

namespace App\Services;

use App\Services\HabitsService;

class ReportsService
{
    private $habitsService;

    public function __construct(HabitsService $habitsService)
    {
        $this->habitsService = $habitsService;
    }

    public function getProgressByDate($date)
    {
        $habits = $this->habitsService->getAllHabitsByDate($date);
        if (empty($habits)) return 0;

        $progress = collect($habits)
            ->filter(fn($habit) => $habit->created_at < now()->startOfDay())
            ->avg(fn($habit) => $habit->report->completed_units / $habit->units);

        return round($progress * 100, 2);
    }

    public function getTodayProgress()
    {
        $today = $this->getProgressByDate(now()->startOfDay());
        $yesterday = $this->getProgressByDate(now()->subDay()->startOfDay());
        $difference = $today - $yesterday;

        return [
            'today' => $today,
            'message' => round(abs($difference), 2) . '% ' . ($difference >= 0 ? 'more' : 'less') . ' than yesterday'
        ];
    }

    public function getLastWeekProgress()
    {
        // Get this week's progress (Monday to current day)
        $thisWeekProgress = collect()
            ->range(0, now()->dayOfWeek - 1)
            ->map(fn($daysAgo) => $this->getProgressByDate(now()->subDays($daysAgo)->startOfDay()))
            ->avg();

        // Get last week's progress (Monday to Sunday)
        $lastWeekProgress = collect()
            ->range(7, 13)
            ->map(fn($daysAgo) => $this->getProgressByDate(now()->subDays($daysAgo)->startOfDay()))
            ->avg();

        $difference = $thisWeekProgress - $lastWeekProgress;

        return [
            'this_week' => round($thisWeekProgress, 2),
            'message' => round(abs($difference), 2) . '% ' . ($difference >= 0 ? 'better' : 'worse') . ' than last week'
        ];
    }

    public function getLastMonthProgress()
    {
        // Get this month's progress (1st to current day)
        $thisMonthProgress = collect()
            ->range(0, now()->day - 1)
            ->map(fn($daysAgo) => $this->getProgressByDate(now()->subDays($daysAgo)->startOfDay()))
            ->avg();

        // Get last month's progress (all days of previous month)
        $lastMonthProgress = collect()
            ->range(1, now()->subMonth()->daysInMonth)
            ->map(function($day) {
                \Log::info(now()->subMonth()->startOfMonth()->addDays($day - 1)->startOfDay());
                return $this->getProgressByDate(
                    now()->subMonth()->startOfMonth()->addDays($day - 1)->startOfDay()
                );
            })
            ->avg();

        $difference = $thisMonthProgress - $lastMonthProgress;

        return [
            'this_month' => round($thisMonthProgress, 2),
            'message' => round(abs($difference), 2) . '% ' . ($difference >= 0 ? 'better' : 'worse') . ' than last month'
        ];
    }
}

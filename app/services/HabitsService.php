<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\Report;

class HabitsService
{
    public function getTodaysHabits()
    {
        $date = now()->startOfDay();
        $habits = Habit::all();
        $todaysHabits = [];
        foreach ($habits as $habit) {
            if ($habit->isScheduledForDate($date) && !$habit->isCompletedForDate($date)) {
                $report = $habit->reports()->where('date', $date)->first();
                if (!$report) {
                    $report = new Report();
                    $report->habit_id = $habit->id;
                    $report->completed_units = 0;
                    $report->date = $date;
                    $habit->reports()->save($report);
                }
                $habit->report = $report;
                $todaysHabits[] = $habit;
            }
        }
        return $todaysHabits;
    }
    public function getAllHabitsByDate($date)
    {
        $habits = Habit::all();
        $todaysHabits = [];
        foreach ($habits as $habit) {
            if ($habit->isScheduledForDate($date)) {
                $report = $habit->reports()->where('date', $date)->first();
                if (!$report) {
                    $report = new Report();
                    $report->habit_id = $habit->id;
                    $report->completed_units = 0;
                    $report->date = $date;
                    $habit->reports()->save($report);
                }
                $habit->report = $report;
                $todaysHabits[] = $habit;
            }
        }
        return $todaysHabits;
    }
}

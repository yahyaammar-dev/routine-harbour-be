<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\HabitsService;
use App\Services\ReportsService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(ReportsService $reportsService)
    {
        $todaysProgress = $reportsService->getTodayProgress();
        $lastWeekProgress = $reportsService->getLastWeekProgress();
        $lastMonthProgress = $reportsService->getLastMonthProgress();

        return response()->json([
            'today' => $todaysProgress['today'],
            'todaysMessage' => $todaysProgress['message'],
            'lastWeek'  => $lastWeekProgress['this_week'],
            'lastWeekMessage' => $lastWeekProgress['message'],
            'lastMonth' => $lastMonthProgress['this_month'],
            'lastMonthMessage' => $lastMonthProgress['message']
        ]);
    }
}

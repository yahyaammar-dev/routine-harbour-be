<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Services\HabitsService;
use Illuminate\Http\Request;

class HabitController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HabitsService $habitsService)
    {
        $habits = Habit::all();
        $todaysHabits = $habitsService->getTodaysHabits();

        return response()->json([
            'all_habits' => $habits,
            'todays_habits' => $todaysHabits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $habit = Habit::create($request->all());
        return response()->json($habit, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $habit = Habit::find($id);
        return response()->json($habit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $habit = Habit::find($id);
        $habit->update($request->all());
        return response()->json($habit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $habit = Habit::find($id);
        $habit->reports()->delete();
        $habit->delete();
        return response()->json(['message' => 'Habit deleted successfully']);
    }

    /**
     * Sync habits with the database
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
        return response()->json(['message' => 'Syncing habits']);
    }

    /**
     * Increase the number of completed units for a habit
     *
     * @param  int  $habitId
     * @return \Illuminate\Http\Response
     */
    public function increase($habitId)
    {
        $habit = Habit::find($habitId);
        $date = now()->startOfDay();
        $report = $habit->reports()->where('date', $date)->first();
        $report->completed_units++;
        $report->save();
        return response()->json($report);
    }
}

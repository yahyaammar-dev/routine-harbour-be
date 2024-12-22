<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $table = 'habits';

    protected $fillable = ['name', 'units', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'hidden'];

    /**
     * Get all reports for this habit
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if habit is scheduled for a given date
     */
    public function isScheduledForDate($date)
    {
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        return $this->$dayOfWeek;
    }

    public function isCompletedForDate($date)
    {
        $report = $this->reports()->where('date', $date)->first();
        return $report ? $report->completed_units >= $this->units : false;
    }
}

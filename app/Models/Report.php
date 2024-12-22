<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';

    protected $fillable = ['habit_id', 'date', 'completed_units'];

    /**
     * Get the habit that owns this report
     */
    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}

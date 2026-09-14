<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'working_minutes',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'email',
        'phone',
        'employee_code',
        'joining_date',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Department Relationship
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Relationship
    |--------------------------------------------------------------------------
    */

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'employee_id'
        );
    }
}
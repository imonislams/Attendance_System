<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'shift_id',
        'designation',
        'name',
        'email',
        'phone',
        'employee_code',
        'joining_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | User Relationship
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
    | Shift Relationship
    |--------------------------------------------------------------------------
    */

    public function shift()
    {
        return $this->belongsTo(Shift::class);
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

    /*
    |--------------------------------------------------------------------------
    | Leave Relationship
    |--------------------------------------------------------------------------
    */

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}

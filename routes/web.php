<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    Route::get('/employees', [
        EmployeeController::class,
        'index'
    ]);

    Route::get('/employees/create', [
        EmployeeController::class,
        'create'
    ]);

    Route::post('/employees', [
        EmployeeController::class,
        'store'
    ]);

    Route::get('/employees/{id}', [
        EmployeeController::class,
        'show'
    ]);

    Route::get('/employees/{id}/edit', [
        EmployeeController::class,
        'edit'
    ]);

    Route::put('/employees/{id}', [
        EmployeeController::class,
        'update'
    ]);

    Route::delete('/employees/{id}', [
        EmployeeController::class,
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    Route::get('/departments', [
        DepartmentController::class,
        'index'
    ]);

    Route::get('/departments/create', [
        DepartmentController::class,
        'create'
    ]);

    Route::post('/departments', [
        DepartmentController::class,
        'store'
    ]);

    Route::get('/departments/{id}/edit', [
        DepartmentController::class,
        'edit'
    ]);

    Route::put('/departments/{id}', [
        DepartmentController::class,
        'update'
    ]);

    Route::delete('/departments/{id}', [
        DepartmentController::class,
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Shift
    |--------------------------------------------------------------------------
    */

    Route::get('/shifts', [
        ShiftController::class,
        'index'
    ]);

    Route::get('/shifts/create', [
        ShiftController::class,
        'create'
    ]);

    Route::post('/shifts', [
        ShiftController::class,
        'store'
    ]);

    Route::get('/shifts/{id}/edit', [
        ShiftController::class,
        'edit'
    ]);

    Route::put('/shifts/{id}', [
        ShiftController::class,
        'update'
    ]);

    Route::delete('/shifts/{id}', [
        ShiftController::class,
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    Route::get('/attendance', [
        AttendanceController::class,
        'index'
    ]);

    Route::get('/attendance/check-in', [
        AttendanceController::class,
        'checkIn'
    ]);

    Route::post('/attendance/check-in', [
        AttendanceController::class,
        'storeCheckIn'
    ]);

    Route::post('/attendance/{id}/check-out', [
        AttendanceController::class,
        'checkOut'
    ]);

    Route::get('/attendance/history', [
        AttendanceController::class,
        'history'
    ]);

    Route::get('/attendance/{id}/edit', [
        AttendanceController::class,
        'edit'
    ]);

    Route::put('/attendance/{id}', [
        AttendanceController::class,
        'update'
    ]);

    Route::delete('/attendance/{id}', [
        AttendanceController::class,
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::get('/reports/daily', [
        ReportController::class,
        'daily'
    ]);

    Route::get('/reports/monthly', [
        ReportController::class,
        'monthly'
    ]);

    Route::get('/reports/employee', [
        ReportController::class,
        'employee'
    ]);

});
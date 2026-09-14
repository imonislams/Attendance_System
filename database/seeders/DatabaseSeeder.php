<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $it = Department::updateOrCreate(
            ['name' => 'IT'],
            [
                'description' => 'Information Technology'
            ]
        );

        $hr = Department::updateOrCreate(
            ['name' => 'HR'],
            [
                'description' => 'Human Resources'
            ]
        );

        $accounts = Department::updateOrCreate(
            ['name' => 'Accounts'],
            [
                'description' => 'Accounts Department'
            ]
        );


        Shift::updateOrCreate(
            ['name' => 'Morning'],
            [
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'late_after' => '09:15:00',
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Evening'],
            [
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'late_after' => '14:15:00',
            ]
        );


        Employee::updateOrCreate(
            ['email' => 'rahim@example.com'],
            [
                'department_id' => $it->id,
                'name' => 'Rahim Ahmed',
                'phone' => '01711111111',
                'employee_code' => 'EMP001',
                'joining_date' => '2026-01-01',
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'karim@example.com'],
            [
                'department_id' => $hr->id,
                'name' => 'Karim Hasan',
                'phone' => '01722222222',
                'employee_code' => 'EMP002',
                'joining_date' => '2026-01-05',
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'sumi@example.com'],
            [
                'department_id' => $accounts->id,
                'name' => 'Sumi Akter',
                'phone' => '01733333333',
                'employee_code' => 'EMP003',
                'joining_date' => '2026-01-10',
            ]
        );


        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
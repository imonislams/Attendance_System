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


        $morningShift = Shift::updateOrCreate(
            ['name' => 'Morning'],
            [
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'late_after' => '09:15:00',
            ]
        );

        $eveningShift = Shift::updateOrCreate(
            ['name' => 'Evening'],
            [
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'late_after' => '14:15:00',
            ]
        );

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Employee 1
        $user1 = User::updateOrCreate(
            ['email' => 'rahim@example.com'],
            [
                'name' => 'Rahim Ahmed',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        Employee::updateOrCreate(
            ['employee_code' => 'EMP001'],
            [
                'user_id' => $user1->id,
                'department_id' => $it->id,
                'shift_id' => $morningShift->id,
                'designation' => 'Software Engineer',
                'name' => 'Rahim Ahmed',
                'email' => 'rahim@example.com',
                'phone' => '01711111111',
                'joining_date' => '2026-01-01',
                'status' => 'active',
            ]
        );

        // Employee 2
        $user2 = User::updateOrCreate(
            ['email' => 'karim@example.com'],
            [
                'name' => 'Karim Hasan',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        Employee::updateOrCreate(
            ['employee_code' => 'EMP002'],
            [
                'user_id' => $user2->id,
                'department_id' => $hr->id,
                'shift_id' => $morningShift->id,
                'designation' => 'HR Executive',
                'name' => 'Karim Hasan',
                'email' => 'karim@example.com',
                'phone' => '01722222222',
                'joining_date' => '2026-01-05',
                'status' => 'active',
            ]
        );

        // Employee 3
        $user3 = User::updateOrCreate(
            ['email' => 'sumi@example.com'],
            [
                'name' => 'Sumi Akter',
                'password' => Hash::make('password'),
                'role' => 'employee',
            ]
        );

        Employee::updateOrCreate(
            ['employee_code' => 'EMP003'],
            [
                'user_id' => $user3->id,
                'department_id' => $accounts->id,
                'shift_id' => $eveningShift->id,
                'designation' => 'Accountant',
                'name' => 'Sumi Akter',
                'email' => 'sumi@example.com',
                'phone' => '01733333333',
                'joining_date' => '2026-01-10',
                'status' => 'active',
            ]
        );
    }
}

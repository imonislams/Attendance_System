<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SystemAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_and_dashboard_access_without_employee_profile()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Admin');
        $dashboardResponse->assertDontSee('Account Setup Pending');
    }

    public function test_employee_login_with_employee_code()
    {
        $department = Department::create(['name' => 'IT']);
        $shift = Shift::create([
            'name' => 'Morning',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'late_after' => '09:15:00',
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'shift_id' => $shift->id,
            'employee_code' => 'EMP100',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'joining_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'EMP100',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboardResponse = $this->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('EMP100');
    }

    public function test_unlinked_employee_user_shows_no_employee_warning()
    {
        $user = User::create([
            'name' => 'Unlinked Employee',
            'email' => 'unlinked@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Account Setup Pending');
    }

    public function test_employee_cannot_access_admin_routes()
    {
        $user = User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        $this->actingAs($user);

        $response = $this->get('/employees');
        $response->assertRedirect('/dashboard');
    }
}

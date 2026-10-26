<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $employee1;
    protected User $employee2;
    protected Project $project;
    protected Task $task1;
    protected Task $task2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'test_admin@example.com'],
            ['name' => 'Test Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );
        $this->admin->forceFill(['role' => 'admin'])->save();

        $this->employee1 = User::firstOrCreate(
            ['email' => 'test_emp1@example.com'],
            ['name' => 'Emp One', 'password' => bcrypt('password'), 'role' => 'employee']
        );
        $this->employee1->forceFill(['role' => 'employee'])->save();

        $this->employee2 = User::firstOrCreate(
            ['email' => 'test_emp2@example.com'],
            ['name' => 'Emp Two', 'password' => bcrypt('password'), 'role' => 'employee']
        );
        $this->employee2->forceFill(['role' => 'employee'])->save();

        $this->project = Project::firstOrCreate(
            ['name' => 'Test Project'],
            ['description' => 'Test description', 'created_by' => $this->admin->id]
        );

        $this->task1 = Task::firstOrCreate(
            ['title' => 'Task For Emp 1'],
            [
                'project_id' => $this->project->id,
                'assigned_to' => $this->employee1->id,
                'created_by' => $this->admin->id,
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->addDays(3),
            ]
        );

        $this->task2 = Task::firstOrCreate(
            ['title' => 'Task For Emp 2'],
            [
                'project_id' => $this->project->id,
                'assigned_to' => $this->employee2->id,
                'created_by' => $this->admin->id,
                'priority' => 'medium',
                'status' => 'pending',
                'due_date' => now()->addDays(5),
            ]
        );
    }

    public function test_admin_can_access_projects_and_tasks(): void
    {
        $response = $this->actingAs($this->admin)->get('/projects');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/tasks');
        $response->assertStatus(200);
    }

    public function test_employee_cannot_access_admin_tasks_and_projects(): void
    {
        $response = $this->actingAs($this->employee1)->get('/tasks');
        $response->assertStatus(403);

        $response = $this->actingAs($this->employee1)->get('/projects');
        $response->assertStatus(403);
    }

    public function test_employee_can_view_own_tasks_only(): void
    {
        $response = $this->actingAs($this->employee1)->get('/my-tasks');
        $response->assertStatus(200);
        $response->assertSee('Task For Emp 1');
        $response->assertDontSee('Task For Emp 2');
    }

    public function test_employee_cannot_view_or_update_other_employees_task(): void
    {
        // Try viewing task 2 belonging to emp 2
        $response = $this->actingAs($this->employee1)->get('/my-tasks/' . $this->task2->id);
        $response->assertStatus(403);

        // Try updating status of task 2
        $response = $this->actingAs($this->employee1)->patchJson('/my-tasks/' . $this->task2->id . '/status', [
            'status' => 'completed'
        ]);
        $response->assertStatus(403);
    }

    public function test_employee_can_update_own_task_status_via_ajax(): void
    {
        $response = $this->actingAs($this->employee1)->patchJson('/my-tasks/' . $this->task1->id . '/status', [
            'status' => 'completed'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Status updated successfully',
            'status' => 'completed',
        ]);

        $this->assertEquals('completed', $this->task1->fresh()->status);
    }

    public function test_api_login_and_task_endpoints(): void
    {
        // Login API
        $loginRes = $this->postJson('/api/v1/login', [
            'email' => 'test_emp1@example.com',
            'password' => 'password',
        ]);
        $loginRes->assertStatus(200);
        $token = $loginRes->json('token');
        $this->assertNotEmpty($token);

        // Fetch tasks with token
        $taskRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/tasks');
        $taskRes->assertStatus(200);

        // Update task status via API
        $statusRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/v1/tasks/' . $this->task1->id . '/status', [
                'status' => 'in_progress',
            ]);
        $statusRes->assertStatus(200);
        $this->assertEquals('in_progress', $this->task1->fresh()->status);

        // Unauthorized task access via API
        $unauthRes = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/v1/tasks/' . $this->task2->id . '/status', [
                'status' => 'completed',
            ]);
        $unauthRes->assertStatus(403);
    }
}

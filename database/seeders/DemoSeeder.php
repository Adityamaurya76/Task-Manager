<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Seed demo data for testing.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
            ]
        );
        $admin->forceFill(['role' => 'admin'])->save();

        // Employees
        $emp1 = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Demo Employee',
                'password' => Hash::make('Employee@12345'),
                'role' => 'employee',
            ]
        );
        $emp1->forceFill(['role' => 'employee'])->save();

        $emp2 = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('Employee@12345'),
                'role' => 'employee',
            ]
        );
        $emp2->forceFill(['role' => 'employee'])->save();

        $emp3 = User::firstOrCreate(
            ['email' => 'priya@example.com'],
            [
                'name' => 'Priya Sharma',
                'password' => Hash::make('Employee@12345'),
                'role' => 'employee',
            ]
        );
        $emp3->forceFill(['role' => 'employee'])->save();

        // Projects
        $p1 = Project::firstOrCreate(
            ['name' => 'Website Redesign'],
            ['description' => 'Complete redesign of the company website with modern UI/UX.', 'created_by' => $admin->id]
        );

        $p2 = Project::firstOrCreate(
            ['name' => 'Mobile App Development'],
            ['description' => 'Build a cross-platform mobile application for customers.', 'created_by' => $admin->id]
        );

        $p3 = Project::firstOrCreate(
            ['name' => 'API Integration'],
            ['description' => 'Integrate third-party payment and notification APIs.', 'created_by' => $admin->id]
        );

        // Seed tasks
        $tasks = [
            // Website Redesign tasks
            ['project_id' => $p1->id, 'assigned_to' => $emp1->id, 'title' => 'Design homepage mockup', 'description' => 'Create wireframes and high-fidelity mockups for the homepage.', 'priority' => 'high', 'status' => 'in_progress', 'due_date' => now()->addDays(5)],
            ['project_id' => $p1->id, 'assigned_to' => $emp1->id, 'title' => 'Implement login page', 'description' => 'Build the login page with form validation.', 'priority' => 'high', 'status' => 'completed', 'due_date' => now()->subDays(2)],
            ['project_id' => $p1->id, 'assigned_to' => $emp2->id, 'title' => 'Create contact form', 'description' => 'Build contact form with email notification.', 'priority' => 'medium', 'status' => 'pending', 'due_date' => now()->addDays(10)],
            ['project_id' => $p1->id, 'assigned_to' => $emp3->id, 'title' => 'SEO optimization', 'description' => 'Add meta tags, sitemap, and optimize page load speed.', 'priority' => 'low', 'status' => 'pending', 'due_date' => now()->addDays(14)],

            // Mobile App tasks
            ['project_id' => $p2->id, 'assigned_to' => $emp2->id, 'title' => 'Setup project structure', 'description' => 'Initialize the mobile app project with required dependencies.', 'priority' => 'high', 'status' => 'completed', 'due_date' => now()->subDays(5)],
            ['project_id' => $p2->id, 'assigned_to' => $emp2->id, 'title' => 'Build user authentication', 'description' => 'Implement login, register, and forgot password screens.', 'priority' => 'high', 'status' => 'in_progress', 'due_date' => now()->addDays(3)],
            ['project_id' => $p2->id, 'assigned_to' => $emp3->id, 'title' => 'Design dashboard screen', 'description' => 'Create the main dashboard layout for the mobile app.', 'priority' => 'medium', 'status' => 'pending', 'due_date' => now()->addDays(7)],

            // API Integration tasks
            ['project_id' => $p3->id, 'assigned_to' => $emp1->id, 'title' => 'Integrate payment gateway', 'description' => 'Integrate Razorpay payment gateway with webhook support.', 'priority' => 'high', 'status' => 'pending', 'due_date' => now()->addDays(8)],
            ['project_id' => $p3->id, 'assigned_to' => $emp3->id, 'title' => 'Setup push notifications', 'description' => 'Integrate Firebase Cloud Messaging for push notifications.', 'priority' => 'medium', 'status' => 'in_progress', 'due_date' => now()->addDays(6)],
            ['project_id' => $p3->id, 'assigned_to' => $emp2->id, 'title' => 'Write API documentation', 'description' => 'Document all API endpoints with request/response examples.', 'priority' => 'low', 'status' => 'pending', 'due_date' => now()->addDays(12)],
        ];

        foreach ($tasks as $taskData) {
            Task::firstOrCreate(
                [
                    'project_id' => $taskData['project_id'],
                    'assigned_to' => $taskData['assigned_to'],
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['created_by' => $admin->id])
            );
        }
    }
}

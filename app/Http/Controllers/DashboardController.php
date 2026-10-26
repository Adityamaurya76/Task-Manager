<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = $request->user()->role === 'admin';

        $query = Task::query();

        if (!$isAdmin) {
            $query->where('assigned_to', $request->user()->id);
        }

        $stats = [
            'total_tasks' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
        ];

        $projectsCount = $isAdmin ? Project::count() : null;
        $employeesCount = $isAdmin ? User::where('role', 'employee')->count() : null;

        return view('dashboard', compact('stats', 'isAdmin', 'projectsCount', 'employeesCount'));
    }
}

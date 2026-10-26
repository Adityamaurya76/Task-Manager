<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'employee']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $employees = User::where('role', 'employee')->get();

        return view('tasks.create', compact('projects', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => ['required',Rule::exists('users', 'id')->where('role', 'employee'),],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $data['created_by'] = $request->user()->id;

        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    
    // Employee task list
    public function myTasks(Request $request)
    {
        $tasks = Task::with('project')->where('assigned_to', $request->user()->id)->latest()->paginate(10);

        return view('tasks.my-tasks', compact('tasks'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Task $task)
    {
        abort_unless($request->user()->role === 'admin' || $task->assigned_to === $request->user()->id, 403);

        return view('tasks.show', compact('task'));
    }

     // Employee: change only status of their own task
    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $data['status']]);

        return response()->json(['message' => 'Status updated successfully', 'status' => $task->status,]);
    }

    // Admin: edit form
    public function edit(Task $task)
    {
        $projects = Project::all();
        $employees = User::where('role', 'employee')->get();

        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    // Admin: update all editable task fields
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => ['required',
                Rule::exists('users', 'id')->where('role', 'employee'),
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('project:id,name');

        if ($request->user()->role === 'employee') {
            $query->where('assigned_to', $request->user()->id);
        }

        if ($request->filled('status')) {
            $request->validate([
                'status' => 'in:pending,in_progress,completed',
            ]);

            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function show(Request $request, Task $task)
    {
        abort_unless($request->user()->role === 'admin' ||
            $task->assigned_to === $request->user()->id,
            403
        );

        return response()->json($task->load('project', 'employee'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless(
            $request->user()->role === 'admin' ||
            $task->assigned_to === $request->user()->id,
            403
        );

        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $data['status']]);

        return response()->json(['message' => 'Task status updated', 'data' => $task,]);
    }
}

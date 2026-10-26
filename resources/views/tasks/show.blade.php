@extends('layouts.app')

@section('header')
    <h4 class="mb-0 fw-bold"><i class="bi bi-eye me-1"></i> Task Details</h4>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">{{ $task->title }}</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Project</p>
                            <p class="fw-semibold mb-0">{{ $task->project->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Priority</p>
                            @php
                                $priorityColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $priorityColors[$task->priority] }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Status</p>
                            @php
                                $statusColors = ['pending' => 'secondary', 'in_progress' => 'info', 'completed' => 'success'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$task->status] }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Due Date</p>
                            <p class="fw-semibold mb-0">{{ $task->due_date?->format('d M Y') ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Created</p>
                            <p class="fw-semibold mb-0">{{ $task->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($task->description)
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Description</p>
                            <p class="mb-0">{{ $task->description }}</p>
                        </div>
                    @endif

                    <hr>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to All Tasks
                        </a>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm ms-2">
                            <i class="bi bi-pencil me-1"></i> Edit Task
                        </a>
                    @else
                        <a href="{{ route('my-tasks') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to My Tasks
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

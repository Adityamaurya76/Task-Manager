@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold"><i class="bi bi-folder me-1"></i> Projects</h4>
        <a href="{{ route('projects.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> New Project
        </a>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Tasks</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td class="fw-semibold">{{ $project->name }}</td>
                                <td class="text-muted">{{ Str::limit($project->description, 50) ?? '—' }}</td>
                                <td><span class="badge bg-secondary">{{ $project->tasks_count ?? $project->tasks->count() }}</span></td>
                                <td>{{ $project->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('projects.destroy', $project) }}"
                                          class="d-inline" onsubmit="return confirm('Delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endsection

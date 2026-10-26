@extends('layouts.app')

@section('header')
    <h4 class="mb-0 fw-bold"><i class="bi bi-check2-square me-1"></i> My Tasks</h4>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="fw-semibold">{{ $task->title }}</td>
                                <td>{{ $task->project->name }}</td>
                                <td>
                                    @php
                                        $priorityColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger'];
                                    @endphp
                                    <span class="badge bg-{{ $priorityColors[$task->priority] }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    <select class="form-select form-select-sm task-status-select"
                                            data-task-id="{{ $task->id }}" style="width:140px">
                                        @foreach(['pending', 'in_progress', 'completed'] as $status)
                                            <option value="{{ $status }}" @selected($task->status === $status)>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="status-msg text-success d-block mt-1" style="display:none!important"></small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('my-tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No tasks assigned to you.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $tasks->links() }}
    </div>

    {{-- AJAX Status Update --}}
    <script>
    document.querySelectorAll('.task-status-select').forEach(select => {
        select.dataset.previous = select.value;

        select.addEventListener('change', async function () {
            const taskId = this.dataset.taskId;
            const msgEl = this.parentElement.querySelector('.status-msg');

            try {
                const response = await fetch(`/my-tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: this.value })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Update failed');
                }

                this.dataset.previous = this.value;
                msgEl.style.cssText = 'display:block!important';
                msgEl.className = 'status-msg text-success d-block mt-1 small';
                msgEl.textContent = '✓ Updated';

                setTimeout(() => { msgEl.style.cssText = 'display:none!important'; }, 2000);
            } catch (error) {
                msgEl.style.cssText = 'display:block!important';
                msgEl.className = 'status-msg text-danger d-block mt-1 small';
                msgEl.textContent = '✗ ' + error.message;
                this.value = this.dataset.previous;

                setTimeout(() => { msgEl.style.cssText = 'display:none!important'; }, 3000);
            }
        });
    });
    </script>
@endsection
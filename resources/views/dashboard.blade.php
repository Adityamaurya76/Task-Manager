@extends('layouts.app')

@section('header')
    <h4 class="mb-0 fw-bold"><i class="bi bi-speedometer2 me-1"></i> Dashboard</h4>
@endsection

@section('content')
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Total Tasks</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_tasks'] }}</h2>
                        </div>
                        <i class="bi bi-list-task fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Pending</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['pending'] }}</h2>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">In Progress</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['in_progress'] }}</h2>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1 opacity-75">Completed</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['completed'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isAdmin)
    <div class="row g-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-folder fs-2 text-primary"></i>
                    <h3 class="fw-bold mt-2">{{ $projectsCount }}</h3>
                    <p class="text-muted mb-0">Projects</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-2 text-primary"></i>
                    <h3 class="fw-bold mt-2">{{ $employeesCount }}</h3>
                    <p class="text-muted mb-0">Employees</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Task Manager') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .stat-card { border: none; border-radius: 12px; transition: transform .15s; }
        .stat-card:hover { transform: translateY(-3px); }
        .table th { white-space: nowrap; }
        .badge { font-weight: 500; }
        .pagination { margin-bottom: 0; }
        nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; max-width: 1.25rem; }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <!-- Page Heading -->
    @hasSection('header')
        <div class="bg-white shadow-sm border-bottom mb-4">
            <div class="container py-3">
                @yield('header')
            </div>
        </div>
    @elseif(isset($header))
        <div class="bg-white shadow-sm border-bottom mb-4">
            <div class="container py-3">
                {{ $header }}
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main class="container py-3">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content', $slot ?? '')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

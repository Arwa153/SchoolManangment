@extends('layouts.dashboard')

@section('title', __('messages.dashboard') . ' - ' . __('messages.school_manager'))

@section('sidebar')
<a href="{{ route('manager.dashboard') }}" class="nav-link active">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('manager.classes') }}" class="nav-link">
    <i class="fas fa-chalkboard me-2"></i>
    {{ __('messages.classes') }}
</a>
<a href="{{ route('manager.teachers') }}" class="nav-link">
    <i class="fas fa-chalkboard-teacher me-2"></i>
    {{ __('messages.teachers') }}
</a>
<a href="{{ route('manager.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    {{ __('messages.students') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}! 👋</h1>
            <p class="page-subtitle">{{ __('messages.school_manager') }} • {{ now()->format('F j, Y') }} - {{ now()->format('l') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('manager.classes') }}" class="btn btn-outline-primary">
                <i class="fas fa-plus me-2"></i>
                {{ __('messages.create') }} {{ __('messages.class') }}
            </a>
            <a href="{{ route('manager.students') }}" class="btn btn-outline-success">
                <i class="fas fa-user-plus me-2"></i>
                {{ __('messages.add') }} {{ __('messages.students') }}
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.teachers') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.students') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.classes') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-chalkboard fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_parents'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.parent') }}s</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Students -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.recent_students') }}</h5>
                <a href="{{ route('manager.students') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view') }} {{ __('messages.all_students') }}</a>
            </div>
            <div class="card-body">
                @if($recentStudents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentStudents as $student)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $student->name }}</h6>
                                    <small class="text-muted">
                                        {{ __('messages.student_code') }}: {{ $student->student_code }}
                                        @if($student->schoolClass)
                                            | {{ __('messages.class') }}: {{ $student->schoolClass->name }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $student->gender }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('messages.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Teachers -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.recent_teachers') }}</h5>
                <a href="{{ route('manager.teachers') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view') }} {{ __('messages.teachers') }}</a>
            </div>
            <div class="card-body">
                @if($recentTeachers->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentTeachers as $teacher)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $teacher->name }}</h6>
                                    <small class="text-muted">{{ $teacher->subject }}</small>
                                </div>
                                <span class="badge bg-success rounded-pill">{{ __('messages.active') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('messages.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('messages.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('manager.classes') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus-circle me-2"></i>
                            {{ __('messages.create') }} {{ __('messages.class') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('manager.students') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-user-plus me-2"></i>
                            {{ __('messages.add') }} {{ __('messages.students') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('manager.teachers') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            {{ __('messages.view') }} {{ __('messages.teachers') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('manager.students') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-users-cog me-2"></i>
                            {{ __('messages.assign') }} {{ __('messages.students') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
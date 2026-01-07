@extends('layouts.dashboard')

@section('title', __('messages.grades'))

@section('sidebar')
<a href="{{ route('parent.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('parent.grades') }}" class="nav-link active">
    <i class="fas fa-star me-2"></i>
    {{ __('messages.grades') }}
</a>
<a href="{{ route('parent.behaviors') }}" class="nav-link">
    <i class="fas fa-clipboard-list me-2"></i>
    {{ __('messages.behavior_reports') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.my_children_grades') }}</h1>
            <p class="page-subtitle">{{ __('messages.view_academic_progress') }}</p>
        </div>
    </div>
</div>

@forelse($children as $child)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-user-graduate me-2"></i>
                {{ $child->name }}
                @if($child->schoolClass)
                    <span class="badge bg-info ms-2">{{ $child->schoolClass->name }}</span>
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if($child->grades->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Semester</th>
                                <th>Teacher</th>
                                <th>Date</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($child->grades->sortByDesc('created_at') as $grade)
                                <tr>
                                    <td>{{ $grade->subject }}</td>
                                    <td>
                                        <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }} fs-6">
                                            {{ $grade->grade }}%
                                        </span>
                                    </td>
                                    <td>{{ $grade->semester }}</td>
                                    <td>{{ $grade->teacher->name }}</td>
                                    <td>{{ $grade->created_at->format('M j, Y') }}</td>
                                    <td>
                                        @if($grade->notes)
                                            <small class="text-muted">{{ $grade->notes }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Grade Summary -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-1">Average Grade</h6>
                                <h4 class="mb-0 text-primary">{{ number_format($child->grades->avg('grade'), 1) }}%</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-1">Highest Grade</h6>
                                <h4 class="mb-0 text-success">{{ $child->grades->max('grade') }}%</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-1">Total Grades</h6>
                                <h4 class="mb-0 text-info">{{ $child->grades->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No grades recorded yet for {{ $child->name }}.</p>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-child fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No Children Found</h4>
            <p class="text-muted">No students are linked to your account.</p>
        </div>
    </div>
@endforelse
@endsection
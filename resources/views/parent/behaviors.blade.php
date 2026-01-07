@extends('layouts.dashboard')

@section('title', 'Behavior Reports')

@section('sidebar')
<a href="{{ route('parent.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('parent.grades') }}" class="nav-link">
    <i class="fas fa-star me-2"></i>
    Grades
</a>
<a href="{{ route('parent.behaviors') }}" class="nav-link active">
    <i class="fas fa-clipboard-list me-2"></i>
    Behavior Reports
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Behavior Reports</h1>
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
            @if($child->behaviorRecords->count() > 0)
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $child->behaviorRecords->where('type', 'positive')->count() }}</h3>
                                <p class="mb-0">Positive Behaviors</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ $child->behaviorRecords->where('type', 'negative')->count() }}</h3>
                                <p class="mb-0">Areas to Improve</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="timeline">
                    @foreach($child->behaviorRecords->sortByDesc('incident_date') as $record)
                        <div class="timeline-item mb-3">
                            <div class="card border-{{ $record->type == 'positive' ? 'success' : 'warning' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">
                                            @if($record->type == 'positive')
                                                <i class="fas fa-thumbs-up text-success me-2"></i>
                                            @else
                                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            @endif
                                            {{ $record->title }}
                                        </h6>
                                        <span class="badge bg-{{ $record->type == 'positive' ? 'success' : 'warning' }}">
                                            {{ ucfirst($record->type) }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-2">{{ $record->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $record->teacher->name }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $record->incident_date->format('M j, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No behavior records yet for {{ $child->name }}.</p>
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
@extends('layouts.dashboard')

@section('title', 'Child Profile')

@section('sidebar')
<a href="{{ route('parent.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('parent.grades') }}" class="nav-link">
    <i class="fas fa-star me-2"></i>
    Grades
</a>
<a href="{{ route('parent.behaviors') }}" class="nav-link">
    <i class="fas fa-clipboard-list me-2"></i>
    Behavior Reports
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $child->name }}'s Profile</h1>
    <a href="{{ route('parent.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Dashboard
    </a>
</div>

<div class="row g-4">
    <!-- Child Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($child->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ $child->name }}</h4>
                <p class="text-muted mb-3">Student Code: {{ $child->student_code }}</p>
                
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $recentGrades->count() }}</h6>
                            <small class="text-muted">Recent Grades</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $recentBehaviors->count() }}</h6>
                            <small class="text-muted">Recent Records</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">School Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Class</h6>
                    @if($child->schoolClass)
                        <div>
                            <span class="badge bg-info">{{ $child->schoolClass->name }}</span>
                            <br><small class="text-muted">{{ $child->schoolClass->grade_level }}</small>
                        </div>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </div>
                
                @if($child->schoolClass && $child->schoolClass->teacher)
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Class Teacher</h6>
                        <div>
                            <h6 class="mb-0">{{ $child->schoolClass->teacher->name }}</h6>
                            <small class="text-muted">{{ $child->schoolClass->teacher->subject }}</small>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Gender</h6>
                    <span class="badge bg-{{ $child->gender == 'male' ? 'primary' : 'pink' }}">
                        {{ ucfirst($child->gender) }}
                    </span>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Date of Birth</h6>
                    <p class="mb-0">{{ $child->date_of_birth->format('F j, Y') }}</p>
                    <small class="text-muted">{{ $child->date_of_birth->age }} years old</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Performance -->
    <div class="col-md-8">
        <!-- Recent Grades -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Grades</h5>
                <a href="{{ route('parent.grades') }}" class="btn btn-sm btn-outline-primary">View All Grades</a>
            </div>
            <div class="card-body">
                @if($recentGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                                @foreach($recentGrades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject }}</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }}">
                                                {{ $grade->grade }}%
                                            </span>
                                        </td>
                                        <td>{{ $grade->semester }}</td>
                                        <td>{{ $grade->teacher->name }}</td>
                                        <td>{{ $grade->created_at->format('M j') }}</td>
                                        <td>
                                            @if($grade->notes)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($grade->notes, 30) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($child->grades->count() > 0)
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">Overall Average</h6>
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
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No grades recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Behavior Records -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Behavior Reports</h5>
                <a href="{{ route('parent.behaviors') }}" class="btn btn-sm btn-outline-primary">View All Reports</a>
            </div>
            <div class="card-body">
                @if($recentBehaviors->count() > 0)
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
                        @foreach($recentBehaviors as $record)
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
                        <p class="text-muted">No behavior records yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Teacher Profile')

@section('sidebar')
<a href="{{ route('manager.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('manager.classes') }}" class="nav-link">
    <i class="fas fa-chalkboard me-2"></i>
    Classes
</a>
<a href="{{ route('manager.teachers') }}" class="nav-link active">
    <i class="fas fa-chalkboard-teacher me-2"></i>
    Teachers
</a>
<a href="{{ route('manager.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    Students
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Teacher Profile</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.teachers.edit', $teacher->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Edit Teacher
        </a>
        <a href="{{ route('manager.teachers.assign-classes', $teacher->id) }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>
            Assign Classes
        </a>
        <a href="{{ route('manager.teachers') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Teachers
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Teacher Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($teacher->name, 0, 2) }}
                </div>
                <h4 class="mb-1">{{ $teacher->name }}</h4>
                <p class="text-muted mb-3">Teacher ID: {{ $teacher->id }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-info fs-6">{{ $teacher->subject }}</span>
                </div>

                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $teacher->assignedClasses->count() }}</h6>
                            <small class="text-muted">Classes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $teacher->assignedClasses->sum(function($class) { return $class->students->count(); }) }}</h6>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Email</h6>
                    <p class="mb-0">{{ $teacher->email }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Subject Specialization</h6>
                    <span class="badge bg-info">{{ $teacher->subject }}</span>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Account Created</h6>
                    <p class="mb-0">{{ $teacher->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Classes and Students -->
    <div class="col-md-8">
        <!-- Assigned Classes -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assigned Classes ({{ $teacher->assignedClasses->count() }})</h5>
                <a href="{{ route('manager.teachers.assign-classes', $teacher->id) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus me-1"></i>
                    Manage Classes
                </a>
            </div>
            <div class="card-body">
                @if($teacher->assignedClasses->count() > 0)
                    <div class="row g-3">
                        @foreach($teacher->assignedClasses as $class)
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $class->name }}</h6>
                                            <span class="badge bg-primary">{{ $class->grade_level }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $class->students->count() }} / {{ $class->capacity }} students
                                            </small>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                                            </div>
                                        </div>
                                        <a href="{{ route('manager.classes.view', $class->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View Class
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No classes assigned yet.</p>
                        <a href="{{ route('manager.teachers.assign-classes', $teacher->id) }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>
                            Assign Classes
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Teaching Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Teaching Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->assignedClasses->count() }}</h4>
                                <small>Total Classes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->assignedClasses->sum(function($class) { return $class->students->count(); }) }}</h4>
                                <small>Total Students</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->grades->count() }}</h4>
                                <small>Grades Given</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->behaviorRecords->count() }}</h4>
                                <small>Behavior Records</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($teacher->assignedClasses->count() > 0)
                    <div class="mt-4">
                        <h6 class="mb-3">Students by Class</h6>
                        @foreach($teacher->assignedClasses as $class)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold">{{ $class->name }}</span>
                                    <span class="text-muted">{{ $class->students->count() }} students</span>
                                </div>
                                <div class="row g-2">
                                    @foreach($class->students->take(6) as $student)
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 10px;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <small>{{ $student->name }}</small>
                                                @if($student->parent)
                                                    <i class="fas fa-user-friends text-success ms-1" title="Parent registered"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($class->students->count() > 6)
                                        <div class="col-12">
                                            <small class="text-muted">+{{ $class->students->count() - 6 }} more students</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
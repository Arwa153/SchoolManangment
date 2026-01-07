@extends('layouts.dashboard')

@section('title', 'Edit Teacher')

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
    <h1 class="h3 mb-0">Edit Teacher</h1>
    <a href="{{ route('manager.teachers') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Teachers
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Teacher Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manager.teachers.update', $teacher->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $teacher->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $teacher->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject/Specialization</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               value="{{ old('subject', $teacher->subject) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text">Leave blank to keep current password</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Teacher
                        </button>
                        <a href="{{ route('manager.teachers') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assigned Classes</h5>
            </div>
            <div class="card-body">
                @if($teacher->assignedClasses->count() > 0)
                    @foreach($teacher->assignedClasses as $class)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">{{ $class->name }}</h6>
                                <small class="text-muted">{{ $class->grade_level }}</small>
                            </div>
                            <span class="badge bg-info">{{ $class->students->count() }} students</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No classes assigned</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="mb-0">{{ $teacher->assignedClasses->count() }}</h4>
                            <small class="text-muted">Classes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="mb-0">
                                {{ $teacher->assignedClasses->sum(function($class) { return $class->students->count(); }) }}
                            </h4>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
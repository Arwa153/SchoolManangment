@extends('layouts.dashboard')

@section('title', 'Edit Class')

@section('sidebar')
<a href="{{ route('manager.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('manager.classes') }}" class="nav-link active">
    <i class="fas fa-chalkboard me-2"></i>
    Classes
</a>
<a href="{{ route('manager.teachers') }}" class="nav-link">
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
    <h1 class="h3 mb-0">Edit Class</h1>
    <a href="{{ route('manager.classes') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Classes
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Class Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manager.classes.update', $class->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Class Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $class->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <input type="text" class="form-control" id="grade_level" name="grade_level" 
                               value="{{ old('grade_level', $class->grade_level) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">Class Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" 
                               value="{{ old('capacity', $class->capacity) }}" min="1" max="50" required>
                        <div class="form-text">
                            Current enrollment: {{ $class->students->count() }} students
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Primary Teacher (Optional)</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">No primary teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" 
                                        {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->subject }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            This is different from assigned teachers. Multiple teachers can be assigned to teach this class.
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Class
                        </button>
                        <a href="{{ route('manager.classes') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Class Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Name</h6>
                    <p class="mb-0">{{ $class->name }}</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Grade Level</h6>
                    <span class="badge bg-info">{{ $class->grade_level }}</span>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Capacity</h6>
                    <p class="mb-0">{{ $class->capacity }} students</p>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Enrollment</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ $class->students->count() }} / {{ $class->capacity }}</span>
                        <div class="progress flex-grow-1 ms-2" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Primary Teacher</h6>
                    @if($class->teacher)
                        <div>
                            <h6 class="mb-0">{{ $class->teacher->name }}</h6>
                            <small class="text-muted">{{ $class->teacher->subject }}</small>
                        </div>
                    @else
                        <span class="text-muted">No primary teacher assigned</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Assigned Teachers</h6>
                    @if($class->teachers->count() > 0)
                        @foreach($class->teachers as $teacher)
                            <div class="d-flex align-items-center mb-1">
                                <div class="avatar-xs bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 10px;">
                                    {{ substr($teacher->name, 0, 2) }}
                                </div>
                                <small>{{ $teacher->name }}</small>
                            </div>
                        @endforeach
                    @else
                        <span class="text-muted">No teachers assigned</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Created</h6>
                    <p class="mb-0">{{ $class->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('manager.classes.view', $class->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>
                        View Class Details
                    </a>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                        <i class="fas fa-user-plus me-2"></i>
                        Assign Teachers
                    </button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignStudentModal">
                        <i class="fas fa-user-graduate me-2"></i>
                        Assign Students
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Teachers to {{ $class->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.classes.assign-teacher') }}">
                @csrf
                <input type="hidden" name="class_ids[]" value="{{ $class->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Select Teacher</label>
                        <select class="form-select" name="teacher_id" required>
                            <option value="">Choose a teacher</option>
                            @foreach(\App\Models\User::where('role', 'teacher')->get() as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->name }} ({{ $teacher->subject }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This will assign the teacher to teach this class. Teachers can be assigned to multiple classes.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Assign Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Student Modal -->
<div class="modal fade" id="assignStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Students to {{ $class->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.students.assign') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select class="form-select" name="student_id" required>
                            <option value="">Choose a student</option>
                            @foreach(\App\Models\Student::whereNull('class_id')->orWhere('class_id', '!=', $class->id)->get() as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} ({{ $student->student_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Available spots: {{ $class->capacity - $class->students->count() }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Edit Student')

@section('sidebar')
<a href="{{ route('manager.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('manager.classes') }}" class="nav-link">
    <i class="fas fa-chalkboard me-2"></i>
    Classes
</a>
<a href="{{ route('manager.teachers') }}" class="nav-link">
    <i class="fas fa-chalkboard-teacher me-2"></i>
    Teachers
</a>
<a href="{{ route('manager.students') }}" class="nav-link active">
    <i class="fas fa-user-graduate me-2"></i>
    Students
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Student</h1>
    <a href="{{ route('manager.students') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Students
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Student Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manager.students.update', $student->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $student->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="student_code" class="form-label">Student Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="student_code" name="student_code" 
                                   value="{{ old('student_code', $student->student_code) }}"
                                   style="text-transform: uppercase;" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateStudentCode()">
                                <i class="fas fa-random me-1"></i>Generate
                            </button>
                        </div>
                        <div class="form-text">
                            Student code must be unique. Parents use this code to access their child's information.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="{{ old('date_of_birth', $student->date_of_birth->format('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id">
                            <option value="">No class assigned</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                        {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} ({{ $class->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Student
                        </button>
                        <a href="{{ route('manager.students') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Student Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Student Code</h6>
                    <code class="bg-light px-2 py-1 rounded">{{ $student->student_code }}</code>
                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                            onclick="copyToClipboard('{{ $student->student_code }}')" title="Copy Code">
                        <i class="fas fa-copy"></i>
                    </button>
                    <div class="form-text">
                        <small class="text-muted">This code can be edited in the form on the left.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Class</h6>
                    @if($student->schoolClass)
                        <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                        <br><small class="text-muted">{{ $student->schoolClass->grade_level }}</small>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Parent Account</h6>
                    @if($student->parent)
                        <div>
                            <h6 class="mb-0">{{ $student->parent->name }}</h6>
                            <small class="text-muted">{{ $student->parent->email }}</small>
                        </div>
                    @else
                        <span class="text-muted">No parent registered</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Age</h6>
                    <span>{{ $student->date_of_birth->age }} years old</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-success"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 1000);
    });
}

function generateStudentCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = 'STU';
    for (let i = 0; i < 6; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('student_code').value = result;
}

// Auto-uppercase student code input
document.getElementById('student_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection
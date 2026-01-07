@extends('layouts.dashboard')

@section('title', 'Class Details')

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
    <h1 class="h3 mb-0">Class Details</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.classes.edit', $class->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Edit Class
        </a>
        <a href="{{ route('manager.classes') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Classes
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Class Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($class->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ $class->name }}</h4>
                <p class="text-muted mb-3">Class ID: {{ $class->id }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-info fs-6">{{ $class->grade_level }}</span>
                </div>

                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $class->students->count() }}</h6>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $class->capacity }}</h6>
                            <small class="text-muted">Capacity</small>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ number_format(($class->students->count() / $class->capacity) * 100, 1) }}% Full
                    </small>
                </div>
            </div>
        </div>

        <!-- Class Details -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Class Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Grade Level</h6>
                    <span class="badge bg-info">{{ $class->grade_level }}</span>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Capacity</h6>
                    <p class="mb-0">{{ $class->capacity }} students</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Current Enrollment</h6>
                    <p class="mb-0">{{ $class->students->count() }} students</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Available Spots</h6>
                    <p class="mb-0 {{ ($class->capacity - $class->students->count()) > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $class->capacity - $class->students->count() }} spots
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Created</h6>
                    <p class="mb-0">{{ $class->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Teachers and Students -->
    <div class="col-md-8">
        <!-- Assigned Teachers -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assigned Teachers ({{ $class->teachers->count() }})</h5>
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                    <i class="fas fa-plus me-1"></i>
                    Assign Teacher
                </button>
            </div>
            <div class="card-body">
                @if($class->teachers->count() > 0)
                    <div class="row g-3">
                        @foreach($class->teachers as $teacher)
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                {{ substr($teacher->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $teacher->name }}</h6>
                                                <small class="text-muted">{{ $teacher->subject }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('manager.teachers.view', $teacher->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>
                                                View
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="removeTeacherFromClass({{ $teacher->id }}, '{{ $teacher->name }}', {{ $class->id }})">
                                                <i class="fas fa-times me-1"></i>
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No teachers assigned yet.</p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignTeacherModal">
                            <i class="fas fa-plus me-2"></i>
                            Assign Teacher
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Students List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Students ({{ $class->students->count() }})</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignStudentModal">
                    <i class="fas fa-plus me-1"></i>
                    Assign Student
                </button>
            </div>
            <div class="card-body">
                @if($class->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Student Code</th>
                                    <th>Parent</th>
                                    <th>Gender</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $student->name }}</h6>
                                                    <small class="text-muted">{{ $student->date_of_birth->age }} years old</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $student->student_code }}</code>
                                        </td>
                                        <td>
                                            @if($student->parent)
                                                <div>
                                                    <h6 class="mb-0">{{ $student->parent->name }}</h6>
                                                    <small class="text-muted">{{ $student->parent->email }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">No parent</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                                                {{ ucfirst($student->gender) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('manager.students.view', $student->id) }}" 
                                                   class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('manager.students.edit', $student->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-warning" 
                                                        onclick="removeStudentFromClass({{ $student->id }}, '{{ $student->name }}')" title="Remove from Class">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No students assigned yet.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignStudentModal">
                            <i class="fas fa-plus me-2"></i>
                            Assign Student
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Teacher to {{ $class->name }}</h5>
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
                <h5 class="modal-title">Assign Student to {{ $class->name }}</h5>
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

@section('scripts')
<script>
function removeTeacherFromClass(teacherId, teacherName, classId) {
    if (confirm(`Are you sure you want to remove ${teacherName} from this class?`)) {
        // Create a form to submit the removal
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/manager/classes/remove-teacher';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const teacherIdInput = document.createElement('input');
        teacherIdInput.type = 'hidden';
        teacherIdInput.name = 'teacher_id';
        teacherIdInput.value = teacherId;
        
        const classIdInput = document.createElement('input');
        classIdInput.type = 'hidden';
        classIdInput.name = 'class_id';
        classIdInput.value = classId;
        
        form.appendChild(csrfToken);
        form.appendChild(teacherIdInput);
        form.appendChild(classIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function removeStudentFromClass(studentId, studentName) {
    if (confirm(`Are you sure you want to remove ${studentName} from this class?`)) {
        // Create a form to submit the removal
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/manager/students/remove-from-class';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const studentIdInput = document.createElement('input');
        studentIdInput.type = 'hidden';
        studentIdInput.name = 'student_id';
        studentIdInput.value = studentId;
        
        form.appendChild(csrfToken);
        form.appendChild(studentIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
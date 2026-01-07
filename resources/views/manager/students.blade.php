@extends('layouts.dashboard')

@section('title', 'Manage Students')

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
    <h1 class="h3 mb-0">Manage Students</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-plus me-2"></i>
        Add Student
    </button>
</div>

<!-- Students Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Student Code</th>
                        <th>Class</th>
                        <th>Parent</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $student->name }}</h6>
                                        <small class="text-muted">ID: {{ $student->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $student->student_code }}</code>
                                <button class="btn btn-sm btn-outline-secondary ms-1" 
                                        onclick="copyToClipboard('{{ $student->student_code }}')" title="Copy Code">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                            <td>
                                @if($student->schoolClass)
                                    <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="assignToClass({{ $student->id }}, '{{ $student->name }}')">
                                        <i class="fas fa-plus me-1"></i>
                                        Assign
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($student->parent)
                                    <div>
                                        <h6 class="mb-0">{{ $student->parent->name }}</h6>
                                        <small class="text-muted">{{ $student->parent->email }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">No parent registered</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                                    {{ ucfirst($student->gender) }}
                                </span>
                            </td>
                            <td>{{ $student->date_of_birth->format('M j, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('manager.students.view', $student->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.students.edit', $student->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteStudent({{ $student->id }}, '{{ $student->name }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No students found. Add your first student!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.students.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_code" class="form-label">Student Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="student_code" name="student_code" 
                                   placeholder="e.g., STU123ABC" style="text-transform: uppercase;" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateStudentCode()">
                                <i class="fas fa-random me-1"></i>Generate
                            </button>
                        </div>
                        <div class="form-text">
                            Enter a unique student code or click Generate for automatic creation.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class (Optional)</label>
                        <select class="form-select" id="class_id" name="class_id">
                            <option value="">No class assigned</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The student code must be unique and will be used by parents to access their child's information.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Class Modal -->
<div class="modal fade" id="assignClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Student to Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.students.assign') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="assign_student_id" name="student_id">
                    <p>Assign <strong id="assign_student_name"></strong> to a class:</p>
                    <div class="mb-3">
                        <label for="assign_class_id" class="form-label">Select Class</label>
                        <select class="form-select" id="assign_class_id" name="class_id" required>
                            <option value="">Choose a class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }} ({{ $class->grade_level }})
                                    @if($class->teacher)
                                        - {{ $class->teacher->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign to Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteStudentName"></strong>?</p>
                <p class="text-danger">This action cannot be undone. All grades, behavior records, and parent account will be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteStudentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function assignToClass(studentId, studentName) {
    document.getElementById('assign_student_id').value = studentId;
    document.getElementById('assign_student_name').textContent = studentName;
    new bootstrap.Modal(document.getElementById('assignClassModal')).show();
}

function deleteStudent(studentId, studentName) {
    document.getElementById('deleteStudentName').textContent = studentName;
    document.getElementById('deleteStudentForm').action = `/manager/students/${studentId}`;
    new bootstrap.Modal(document.getElementById('deleteStudentModal')).show();
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary success message
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
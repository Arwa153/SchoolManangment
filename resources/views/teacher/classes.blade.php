@extends('layouts.dashboard')

@section('title', 'My Classes')

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('teacher.classes') }}" class="nav-link active">
    <i class="fas fa-chalkboard me-2"></i>
    My Classes
</a>
<a href="{{ route('teacher.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    All Students
</a>
<a href="{{ route('teacher.timetable') }}" class="nav-link">
    <i class="fas fa-calendar-alt me-2"></i>
    My Timetable
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">My Classes</h1>
    <div class="d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGradeModal">
            <i class="fas fa-plus me-2"></i>
            Add Grade
        </button>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addBehaviorModal">
            <i class="fas fa-clipboard-list me-2"></i>
            Add Behavior Record
        </button>
    </div>
</div>

<!-- Classes Grid -->
<div class="row g-4">
    @forelse($assignedClasses as $class)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $class->name }}</h5>
                    <span class="badge bg-primary">{{ $class->grade_level }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Students</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $class->students->count() }} / {{ $class->capacity }}</span>
                            <div class="progress flex-grow-1 ms-2" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($class->students->count() > 0)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Recent Students</h6>
                            @foreach($class->students->take(3) as $student)
                                <div class="d-flex align-items-center mb-1">
                                    <div class="avatar-xs bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 10px;">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <small>{{ $student->name }}</small>
                                    @if($student->parent)
                                        <i class="fas fa-user-friends text-success ms-1" title="Parent registered"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if($class->students->count() > 3)
                                <small class="text-muted">+{{ $class->students->count() - 3 }} more</small>
                            @endif
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.class.students', $class->id) }}" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>
                            View All Students ({{ $class->students->count() }})
                        </a>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-success btn-sm" 
                                    onclick="showAddGradeForClass({{ $class->id }}, '{{ $class->name }}')">
                                <i class="fas fa-plus me-1"></i>
                                Add Grade
                            </button>
                            <button class="btn btn-outline-warning btn-sm" 
                                    onclick="showAddBehaviorForClass({{ $class->id }}, '{{ $class->name }}')">
                                <i class="fas fa-clipboard me-1"></i>
                                Add Behavior
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chalkboard fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Classes Assigned</h4>
                    <p class="text-muted">You haven't been assigned to any classes yet. Please contact your school administrator.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Add Grade Modal -->
<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Grade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('teacher.grades.add') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="grade_class_id" class="form-label">Select Class</label>
                        <select class="form-select" id="grade_class_id" name="class_id" required onchange="loadStudents(this.value, 'grade')">
                            <option value="">Choose a class</option>
                            @foreach($assignedClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="grade_student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="grade_student_id" name="student_id" required disabled>
                            <option value="">First select a class</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               value="{{ auth()->user()->subject }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade (%)</label>
                        <input type="number" class="form-control" id="grade" name="grade" 
                               min="0" max="100" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Third Semester">Third Semester</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Behavior Modal -->
<div class="modal fade" id="addBehaviorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Behavior Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('teacher.behaviors.add') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="behavior_class_id" class="form-label">Select Class</label>
                        <select class="form-select" id="behavior_class_id" name="class_id" required onchange="loadStudents(this.value, 'behavior')">
                            <option value="">Choose a class</option>
                            @foreach($assignedClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="behavior_student_id" class="form-label">Select Student</label>
                        <select class="form-select" id="behavior_student_id" name="student_id" required disabled>
                            <option value="">First select a class</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="positive">Positive Behavior</option>
                            <option value="negative">Needs Improvement</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               placeholder="e.g., Excellent participation, Late to class" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="incident_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="incident_date" name="incident_date" 
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showAddGradeForClass(classId, className) {
    document.getElementById('grade_class_id').value = classId;
    loadStudents(classId, 'grade');
    new bootstrap.Modal(document.getElementById('addGradeModal')).show();
}

function showAddBehaviorForClass(classId, className) {
    document.getElementById('behavior_class_id').value = classId;
    loadStudents(classId, 'behavior');
    new bootstrap.Modal(document.getElementById('addBehaviorModal')).show();
}

function loadStudents(classId, type) {
    const studentSelect = document.getElementById(type + '_student_id');
    
    if (!classId) {
        studentSelect.innerHTML = '<option value="">First select a class</option>';
        studentSelect.disabled = true;
        return;
    }

    studentSelect.innerHTML = '<option value="">Loading students...</option>';
    studentSelect.disabled = true;

    fetch(`/teacher/api/classes/${classId}/students`)
        .then(response => response.json())
        .then(students => {
            studentSelect.innerHTML = '<option value="">Select a student</option>';
            students.forEach(student => {
                studentSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
            });
            studentSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading students:', error);
            studentSelect.innerHTML = '<option value="">Error loading students</option>';
        });
}
</script>
@endsection
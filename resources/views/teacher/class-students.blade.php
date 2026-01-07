@extends('layouts.dashboard')

@section('title', 'Class Students')

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
    <div>
        <h1 class="h3 mb-0">{{ $class->name }} Students</h1>
        <p class="text-muted mb-0">{{ $class->grade_level }} | {{ $class->students->count() }} students</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success" onclick="showAddGradeForClass({{ $class->id }})">
            <i class="fas fa-plus me-2"></i>
            Add Grade
        </button>
        <button class="btn btn-warning" onclick="showAddBehaviorForClass({{ $class->id }})">
            <i class="fas fa-clipboard-list me-2"></i>
            Add Behavior Record
        </button>
        <a href="{{ route('teacher.classes') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Classes
        </a>
    </div>
</div>

<!-- Class Information -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $class->students->count() }}</h3>
                <p class="mb-0">Total Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-2">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $class->students->where('parent_id', '!=', null)->count() }}</h3>
                <p class="mb-0">Parents Registered</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-3">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $class->students->where('gender', 'male')->count() }}</h3>
                <p class="mb-0">Male Students</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-4">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $class->students->where('gender', 'female')->count() }}</h3>
                <p class="mb-0">Female Students</p>
            </div>
        </div>
    </div>
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
                        <th>Gender</th>
                        <th>Parent Status</th>
                        <th>Recent Grades</th>
                        <th>Behavior</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->students as $student)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $student->name }}</h6>
                                        <small class="text-muted">Age: {{ $student->date_of_birth->age }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $student->student_code }}</code>
                            </td>
                            <td>
                                <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                                    {{ ucfirst($student->gender) }}
                                </span>
                            </td>
                            <td>
                                @if($student->parent)
                                    <div>
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        <span class="text-success">Registered</span>
                                        <br><small class="text-muted">{{ $student->parent->name }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Not registered
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($student->grades->count() > 0)
                                    <div>
                                        <span class="fw-bold">{{ $student->grades->count() }} grades</span>
                                        <br><small class="text-muted">
                                            Avg: {{ number_format($student->grades->avg('grade'), 1) }}%
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted">No grades</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($student->behaviorRecords->where('type', 'positive')->count() > 0)
                                        <span class="badge bg-success">
                                            +{{ $student->behaviorRecords->where('type', 'positive')->count() }}
                                        </span>
                                    @endif
                                    @if($student->behaviorRecords->where('type', 'negative')->count() > 0)
                                        <span class="badge bg-warning">
                                            -{{ $student->behaviorRecords->where('type', 'negative')->count() }}
                                        </span>
                                    @endif
                                    @if($student->behaviorRecords->count() == 0)
                                        <span class="text-muted">No records</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-success" 
                                            onclick="addGradeForStudent({{ $student->id }}, '{{ $student->name }}')">
                                        <i class="fas fa-plus me-1"></i>
                                        Grade
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="addBehaviorForStudent({{ $student->id }}, '{{ $student->name }}')">
                                        <i class="fas fa-clipboard me-1"></i>
                                        Behavior
                                    </button>
                                    <a href="{{ route('teacher.student.profile', $student->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No students enrolled in this class yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Behavior Records for this Class -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Behavior Records</h5>
        <button class="btn btn-sm btn-warning" onclick="showAddBehaviorForClass({{ $class->id }})">
            <i class="fas fa-plus me-1"></i>
            Add Record
        </button>
    </div>
    <div class="card-body">
        @php
            $recentBehaviors = $class->students->flatMap(function($student) {
                return $student->behaviorRecords->where('teacher_id', auth()->id());
            })->sortByDesc('incident_date')->take(10);
        @endphp
        
        @if($recentBehaviors->count() > 0)
            <div class="row">
                @foreach($recentBehaviors as $record)
                    <div class="col-md-6 mb-3">
                        <div class="card border-{{ $record->type == 'positive' ? 'success' : 'warning' }} h-100">
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
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $record->type == 'positive' ? 'success' : 'warning' }}">
                                            {{ ucfirst($record->type) }}
                                        </span>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-xs btn-outline-primary" 
                                                    onclick="editBehaviorInClass({{ $record->id }}, '{{ $record->type }}', '{{ addslashes($record->title) }}', '{{ addslashes($record->description) }}', '{{ $record->incident_date->format('Y-m-d') }}')"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-xs btn-outline-danger" 
                                                    onclick="deleteBehaviorInClass({{ $record->id }}, '{{ addslashes($record->title) }}')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted mb-2 small">{{ Str::limit($record->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $record->student->name }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $record->incident_date->format('M j') }}
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
                <p class="text-muted">No behavior records for this class yet.</p>
                <button class="btn btn-warning" onclick="showAddBehaviorForClass({{ $class->id }})">
                    <i class="fas fa-plus me-2"></i>
                    Add First Record
                </button>
            </div>
        @endif
    </div>
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
                    <input type="hidden" id="grade_class_id" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" id="grade_student_id" name="student_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <input type="text" class="form-control" value="{{ $class->name }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" class="form-control" id="grade_student_name" readonly>
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
                    <input type="hidden" id="behavior_class_id" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" id="behavior_student_id" name="student_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <input type="text" class="form-control" value="{{ $class->name }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" class="form-control" id="behavior_student_name" readonly>
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

<!-- Edit Behavior Modal -->
<div class="modal fade" id="editBehaviorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Behavior Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editBehaviorForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="positive">Positive Behavior</option>
                            <option value="negative">Needs Improvement</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" 
                               placeholder="e.g., Excellent participation, Late to class" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" 
                                  rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_incident_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_incident_date" name="incident_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Behavior Confirmation Modal -->
<div class="modal fade" id="deleteBehaviorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Behavior Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this behavior record?</p>
                <p class="text-danger fw-bold" id="deleteBehaviorTitle"></p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteBehaviorForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showAddGradeForClass(classId) {
    new bootstrap.Modal(document.getElementById('addGradeModal')).show();
}

function showAddBehaviorForClass(classId) {
    new bootstrap.Modal(document.getElementById('addBehaviorModal')).show();
}

function addGradeForStudent(studentId, studentName) {
    document.getElementById('grade_student_id').value = studentId;
    document.getElementById('grade_student_name').value = studentName;
    new bootstrap.Modal(document.getElementById('addGradeModal')).show();
}

function addBehaviorForStudent(studentId, studentName) {
    document.getElementById('behavior_student_id').value = studentId;
    document.getElementById('behavior_student_name').value = studentName;
    new bootstrap.Modal(document.getElementById('addBehaviorModal')).show();
}

function editBehaviorInClass(behaviorId, type, title, description, incidentDate) {
    document.getElementById('editBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_incident_date').value = incidentDate;
    new bootstrap.Modal(document.getElementById('editBehaviorModal')).show();
}

function deleteBehaviorInClass(behaviorId, title) {
    document.getElementById('deleteBehaviorTitle').textContent = title;
    document.getElementById('deleteBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    new bootstrap.Modal(document.getElementById('deleteBehaviorModal')).show();
}
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Student Profile')

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('teacher.students') }}" class="nav-link active">
    <i class="fas fa-user-graduate me-2"></i>
    My Students
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $student->name }}'s Academic File</h1>
    <div class="d-flex gap-2">
        <button class="btn btn-success" onclick="addGrade({{ $student->id }}, '{{ $student->name }}')">
            <i class="fas fa-plus me-2"></i>
            Add Grade
        </button>
        <button class="btn btn-warning" onclick="addBehavior({{ $student->id }}, '{{ $student->name }}')">
            <i class="fas fa-clipboard-list me-2"></i>
            Add Behavior Record
        </button>
        <a href="{{ route('teacher.students') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Students
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Student Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ $student->name }}</h4>
                <p class="text-muted mb-3">{{ $student->student_code }}</p>
                
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $student->grades->count() }}</h6>
                            <small class="text-muted">Total Grades</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $student->behaviorRecords->count() }}</h6>
                            <small class="text-muted">Behavior Records</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Student Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Class</h6>
                    @if($student->schoolClass)
                        <div>
                            <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                            <br><small class="text-muted">{{ $student->schoolClass->grade_level }}</small>
                        </div>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Gender</h6>
                    <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                        {{ ucfirst($student->gender) }}
                    </span>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted mb-1">Age</h6>
                    <span>{{ $student->date_of_birth->age }} years old</span>
                </div>

                @if($student->grades->count() > 0)
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Academic Performance</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Average Grade:</span>
                            <span class="fw-bold text-primary">{{ number_format($student->grades->avg('grade'), 1) }}%</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Academic Records -->
    <div class="col-md-8">
        <!-- Grades Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Academic Grades</h5>
                <button class="btn btn-sm btn-success" onclick="addGrade({{ $student->id }}, '{{ $student->name }}')">
                    <i class="fas fa-plus me-1"></i>
                    Add Grade
                </button>
            </div>
            <div class="card-body">
                @if($student->grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                @foreach($student->grades->sortByDesc('created_at') as $grade)
                                    <tr>
                                        <td>{{ $grade->subject }}</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }} fs-6">
                                                {{ $grade->grade }}%
                                            </span>
                                        </td>
                                        <td>{{ $grade->semester }}</td>
                                        <td>
                                            @if($grade->teacher_id == auth()->id())
                                                <span class="text-primary fw-bold">You</span>
                                            @else
                                                {{ $grade->teacher->name }}
                                            @endif
                                        </td>
                                        <td>{{ $grade->created_at->format('M j, Y') }}</td>
                                        <td>
                                            @if($grade->notes)
                                                <small class="text-muted">{{ $grade->notes }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Grade Statistics -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Average</h6>
                                    <h4 class="mb-0 text-primary">{{ number_format($student->grades->avg('grade'), 1) }}%</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Highest</h6>
                                    <h4 class="mb-0 text-success">{{ $student->grades->max('grade') }}%</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Lowest</h6>
                                    <h4 class="mb-0 text-danger">{{ $student->grades->min('grade') }}%</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Total</h6>
                                    <h4 class="mb-0 text-info">{{ $student->grades->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No grades recorded yet.</p>
                        <button class="btn btn-success" onclick="addGrade({{ $student->id }}, '{{ $student->name }}')">
                            <i class="fas fa-plus me-2"></i>
                            Add First Grade
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Behavior Records Section -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Behavior Records</h5>
                <button class="btn btn-sm btn-warning" onclick="addBehavior({{ $student->id }}, '{{ $student->name }}')">
                    <i class="fas fa-plus me-1"></i>
                    Add Record
                </button>
            </div>
            <div class="card-body">
                @if($student->behaviorRecords->count() > 0)
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $student->behaviorRecords->where('type', 'positive')->count() }}</h3>
                                    <p class="mb-0">Positive Behaviors</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ $student->behaviorRecords->where('type', 'negative')->count() }}</h3>
                                    <p class="mb-0">Areas to Improve</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="timeline">
                        @foreach($student->behaviorRecords->sortByDesc('incident_date') as $record)
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
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $record->type == 'positive' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($record->type) }}
                                                </span>
                                                @if($record->teacher_id == auth()->id())
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" 
                                                                onclick="editBehavior({{ $record->id }}, '{{ $record->type }}', '{{ addslashes($record->title) }}', '{{ addslashes($record->description) }}', '{{ $record->incident_date->format('Y-m-d') }}')"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteBehavior({{ $record->id }}, '{{ addslashes($record->title) }}')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-muted mb-2">{{ $record->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                @if($record->teacher_id == auth()->id())
                                                    <span class="text-primary fw-bold">You</span>
                                                @else
                                                    {{ $record->teacher->name }}
                                                @endif
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
                        <button class="btn btn-warning" onclick="addBehavior({{ $student->id }}, '{{ $student->name }}')">
                            <i class="fas fa-plus me-2"></i>
                            Add First Record
                        </button>
                    </div>
                @endif
            </div>
        </div>
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
                    <input type="hidden" id="grade_student_id" name="student_id">
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
                    <input type="hidden" id="behavior_student_id" name="student_id">
                    <input type="hidden" name="class_id" value="{{ $student->class_id }}">
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
function addGrade(studentId, studentName) {
    document.getElementById('grade_student_id').value = studentId;
    document.getElementById('grade_student_name').value = studentName;
    new bootstrap.Modal(document.getElementById('addGradeModal')).show();
}

function addBehavior(studentId, studentName) {
    document.getElementById('behavior_student_id').value = studentId;
    document.getElementById('behavior_student_name').value = studentName;
    new bootstrap.Modal(document.getElementById('addBehaviorModal')).show();
}

function editBehavior(behaviorId, type, title, description, incidentDate) {
    document.getElementById('editBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_incident_date').value = incidentDate;
    new bootstrap.Modal(document.getElementById('editBehaviorModal')).show();
}

function deleteBehavior(behaviorId, title) {
    document.getElementById('deleteBehaviorTitle').textContent = title;
    document.getElementById('deleteBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    new bootstrap.Modal(document.getElementById('deleteBehaviorModal')).show();
}
</script>
@endsection
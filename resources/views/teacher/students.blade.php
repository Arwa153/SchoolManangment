@extends('layouts.dashboard')

@section('title', __('messages.my_students'))

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('teacher.students') }}" class="nav-link active">
    <i class="fas fa-user-graduate me-2"></i>
    {{ __('messages.my_students') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.my_students') }}</h1>
            <p class="page-subtitle">{{ __('messages.manage_student_grades_behavior') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                <i class="fas fa-plus me-2"></i>
                {{ __('messages.add') }} {{ __('messages.grade') }}
            </button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addBehaviorModal">
                <i class="fas fa-clipboard-list me-2"></i>
                {{ __('messages.add') }} {{ __('messages.behavior_record') }}
            </button>
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
                        <th>Class</th>
                        <th>Grades</th>
                        <th>Behavior</th>
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
                                        <small class="text-muted">{{ $student->student_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($student->schoolClass)
                                    <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $student->grades->count() }} grades</span>
                                    @if($student->grades->count() > 0)
                                        <small class="text-muted">
                                            Avg: {{ number_format($student->grades->avg('grade'), 1) }}%
                                        </small>
                                    @endif
                                </div>
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
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-success" 
                                            onclick="addGrade({{ $student->id }}, '{{ $student->name }}')">
                                        <i class="fas fa-plus me-1"></i>
                                        Grade
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" 
                                            onclick="addBehavior({{ $student->id }}, '{{ $student->name }}')">
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
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No students assigned to your classes yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Behavior Records Section -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Recent Behavior Records</h5>
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addBehaviorModal">
            <i class="fas fa-plus me-1"></i>
            Add Record
        </button>
    </div>
    <div class="card-body">
        @php
            $myBehaviors = collect();
            foreach($students as $student) {
                $studentBehaviors = $student->behaviorRecords->where('teacher_id', auth()->id());
                $myBehaviors = $myBehaviors->merge($studentBehaviors);
            }
            $myBehaviors = $myBehaviors->sortByDesc('incident_date')->take(10);
        @endphp
        
        @if($myBehaviors->count() > 0)
            <div class="row">
                @foreach($myBehaviors as $record)
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
                                                    onclick="editBehaviorRecord({{ $record->id }}, '{{ $record->type }}', '{{ addslashes($record->title) }}', '{{ addslashes($record->description) }}', '{{ $record->incident_date->format('Y-m-d') }}')"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-xs btn-outline-danger" 
                                                    onclick="deleteBehaviorRecord({{ $record->id }}, '{{ addslashes($record->title) }}')"
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
                <p class="text-muted">No behavior records created yet.</p>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addBehaviorModal">
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
                    <input type="hidden" id="grade_student_id" name="student_id">
                    <input type="hidden" id="grade_class_id" name="class_id">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" class="form-control" id="grade_student_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <input type="text" class="form-control" id="grade_class_name" readonly>
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
                    <input type="hidden" id="behavior_class_id" name="class_id">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" class="form-control" id="behavior_student_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <input type="text" class="form-control" id="behavior_class_name" readonly>
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
    // Find the student's class from the table
    const studentRow = document.querySelector(`button[onclick="addGrade(${studentId}, '${studentName}')"]`).closest('tr');
    const classElement = studentRow.querySelector('.badge');
    const className = classElement ? classElement.textContent : '';
    
    document.getElementById('grade_student_id').value = studentId;
    document.getElementById('grade_student_name').value = studentName;
    document.getElementById('grade_class_name').value = className;
    
    // Get class ID from the student data
    @foreach($students as $student)
        if ({{ $student->id }} == studentId) {
            document.getElementById('grade_class_id').value = {{ $student->class_id ?? 'null' }};
        }
    @endforeach
    
    new bootstrap.Modal(document.getElementById('addGradeModal')).show();
}

function addBehavior(studentId, studentName) {
    // Find the student's class from the table
    const studentRow = document.querySelector(`button[onclick="addBehavior(${studentId}, '${studentName}')"]`).closest('tr');
    const classElement = studentRow.querySelector('.badge');
    const className = classElement ? classElement.textContent : '';
    
    document.getElementById('behavior_student_id').value = studentId;
    document.getElementById('behavior_student_name').value = studentName;
    document.getElementById('behavior_class_name').value = className;
    
    // Get class ID from the student data
    @foreach($students as $student)
        if ({{ $student->id }} == studentId) {
            document.getElementById('behavior_class_id').value = {{ $student->class_id ?? 'null' }};
        }
    @endforeach
    
    new bootstrap.Modal(document.getElementById('addBehaviorModal')).show();
}

function editBehaviorRecord(behaviorId, type, title, description, incidentDate) {
    document.getElementById('editBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    document.getElementById('edit_type').value = type;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_incident_date').value = incidentDate;
    new bootstrap.Modal(document.getElementById('editBehaviorModal')).show();
}

function deleteBehaviorRecord(behaviorId, title) {
    document.getElementById('deleteBehaviorTitle').textContent = title;
    document.getElementById('deleteBehaviorForm').action = `/teacher/behaviors/${behaviorId}`;
    new bootstrap.Modal(document.getElementById('deleteBehaviorModal')).show();
}
</script>
@endsection
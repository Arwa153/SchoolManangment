@extends('layouts.dashboard')

@section('title', 'Assign Classes to Teacher')

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
    <h1 class="h3 mb-0">Assign Classes to {{ $teacher->name }}</h1>
    <a href="{{ route('manager.teachers.view', $teacher->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Teacher Profile
    </a>
</div>

<!-- Workflow Information -->
<div class="alert alert-info mb-4">
    <div class="d-flex align-items-start">
        <i class="fas fa-info-circle me-2 mt-1"></i>
        <div>
            <h6 class="mb-1">How Teacher Assignment Works</h6>
            <p class="mb-0">
                • <strong>Add Classes:</strong> Select unassigned classes below and click "Add Selected Classes" to assign them to {{ $teacher->name }}<br>
                • <strong>Remove Classes:</strong> Use the × button next to currently assigned classes to remove individual assignments<br>
                • <strong>Multiple Teachers:</strong> Classes can have multiple teachers assigned simultaneously
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($teacher->name, 0, 2) }}
                </div>
                <h4 class="mb-1">{{ $teacher->name }}</h4>
                <p class="text-muted mb-3">{{ $teacher->subject }}</p>
                
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $teacher->assignedClasses->count() }}</h6>
                            <small class="text-muted">Current Classes</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $teacher->assignedClasses->sum(function($class) { return $class->students->count(); }) }}</h6>
                            <small class="text-muted">Total Students</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currently Assigned Classes -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Currently Assigned Classes</h5>
            </div>
            <div class="card-body">
                @if($teacher->assignedClasses->count() > 0)
                    @foreach($teacher->assignedClasses as $class)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div>
                                <h6 class="mb-0">{{ $class->name }}</h6>
                                <small class="text-muted">{{ $class->grade_level }} - {{ $class->students->count() }} students</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="removeClassFromTeacher({{ $class->id }}, '{{ $class->name }}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No classes assigned yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Assign Classes</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manager.classes.assign-teacher') }}">
                    @csrf
                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Classes to Assign</label>
                        <div class="form-text mb-3">
                            Select classes to assign to {{ $teacher->name }}. This will ADD the selected classes to their current assignments without removing existing ones.
                        </div>
                        
                        <div class="row g-3">
                            @foreach($allClasses as $class)
                                <div class="col-md-6">
                                    <div class="card border {{ $teacher->assignedClasses->contains($class->id) ? 'border-success bg-light' : '' }}">
                                        <div class="card-body">
                                            @if($teacher->assignedClasses->contains($class->id))
                                                <!-- Already assigned - show as info only -->
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 text-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            {{ $class->name }}
                                                        </h6>
                                                        <small class="text-muted">{{ $class->grade_level }}</small>
                                                        <div class="mt-1">
                                                            <span class="badge bg-success">Already Assigned</span>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-primary">{{ $class->students->count() }} students</span>
                                                </div>
                                            @else
                                                <!-- Not assigned - show checkbox -->
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="class_ids[]" value="{{ $class->id }}" 
                                                           id="class_{{ $class->id }}">
                                                    <label class="form-check-label w-100" for="class_{{ $class->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1">{{ $class->name }}</h6>
                                                                <small class="text-muted">{{ $class->grade_level }}</small>
                                                            </div>
                                                            <span class="badge bg-primary">{{ $class->students->count() }} students</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endif
                                            
                                            @if($class->teachers->count() > 0)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-users me-1"></i>
                                                        Other teachers: 
                                                        @foreach($class->teachers->where('id', '!=', $teacher->id) as $otherTeacher)
                                                            {{ $otherTeacher->name }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                        @if($class->teachers->where('id', '!=', $teacher->id)->count() == 0)
                                                            None
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <div class="progress mt-2" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $class->students->count() }}/{{ $class->capacity }} capacity</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>
                            Add Selected Classes
                        </button>
                        <a href="{{ route('manager.teachers.view', $teacher->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assignment Summary -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Assignment Summary</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $allClasses->count() }}</h4>
                                <small>Total Classes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->assignedClasses->count() }}</h4>
                                <small>Currently Assigned</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $allClasses->count() - $teacher->assignedClasses->count() }}</h4>
                                <small>Available</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $teacher->assignedClasses->sum(function($class) { return $class->students->count(); }) }}</h4>
                                <small>Total Students</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function removeClassFromTeacher(classId, className) {
    if (confirm(`Are you sure you want to remove ${className} from {{ $teacher->name }}?`)) {
        // Create a form to remove the teacher from this specific class
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("manager.classes.remove-teacher") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add teacher ID
        const teacherIdInput = document.createElement('input');
        teacherIdInput.type = 'hidden';
        teacherIdInput.name = 'teacher_id';
        teacherIdInput.value = '{{ $teacher->id }}';
        form.appendChild(teacherIdInput);
        
        // Add class ID
        const classIdInput = document.createElement('input');
        classIdInput.type = 'hidden';
        classIdInput.name = 'class_id';
        classIdInput.value = classId;
        form.appendChild(classIdInput);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
}

// Add visual feedback when checkboxes are changed
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="class_ids[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.card');
            if (this.checked) {
                card.classList.add('border-primary', 'bg-light');
            } else {
                card.classList.remove('border-primary', 'bg-light');
            }
        });
    });
    
    // Prevent form submission if no classes are selected
    const form = document.querySelector('form[action="{{ route('manager.classes.assign-teacher') }}"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('input[name="class_ids[]"]:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one class to assign.');
            }
        });
    }
});
</script>
@endsection
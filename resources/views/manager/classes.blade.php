@extends('layouts.dashboard')

@section('title', __('messages.manage') . ' ' . __('messages.classes'))

@section('sidebar')
<a href="{{ route('manager.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('manager.classes') }}" class="nav-link active">
    <i class="fas fa-chalkboard me-2"></i>
    {{ __('messages.classes') }}
</a>
<a href="{{ route('manager.teachers') }}" class="nav-link">
    <i class="fas fa-chalkboard-teacher me-2"></i>
    {{ __('messages.teachers') }}
</a>
<a href="{{ route('manager.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    {{ __('messages.students') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.manage') }} {{ __('messages.classes') }}</h1>
            <p class="page-subtitle">{{ __('messages.manage_classes_desc') }}</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="fas fa-plus me-2"></i>
            {{ __('messages.create') }} {{ __('messages.class') }}
        </button>
    </div>
</div>

<!-- Classes Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('messages.class') }} {{ __('messages.name') }}</th>
                        <th>{{ __('messages.grade_level') }}</th>
                        <th>{{ __('messages.teachers') }}</th>
                        <th>{{ __('messages.students') }}</th>
                        <th>{{ __('messages.capacity') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($class->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $class->name }}</h6>
                                        <small class="text-muted">ID: {{ $class->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $class->grade_level }}</span>
                            </td>
                            <td>
                                @if($class->teachers->count() > 0)
                                    <div>
                                        @foreach($class->teachers->take(2) as $teacher)
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="avatar-xs bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 20px; height: 20px; font-size: 8px;">
                                                    {{ substr($teacher->name, 0, 2) }}
                                                </div>
                                                <small>{{ $teacher->name }}</small>
                                            </div>
                                        @endforeach
                                        @if($class->teachers->count() > 2)
                                            <small class="text-muted">+{{ $class->teachers->count() - 2 }} more</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No teachers assigned</span>
                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="assignTeacher({{ $class->id }}, '{{ $class->name }}')">
                                        <i class="fas fa-plus me-1"></i>
                                        Assign
                                    </button>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $class->students->count() }}</span>
                                    <div class="progress flex-grow-1" style="height: 8px; max-width: 100px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $class->capacity }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('manager.classes.view', $class->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View Students">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('manager.classes.edit', $class->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteClass({{ $class->id }}, '{{ $class->name }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No classes found. Create your first class!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.classes.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Class Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="e.g., Class 5A, Mathematics Advanced" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <input type="text" class="form-control" id="grade_level" name="grade_level" 
                               placeholder="e.g., Grade 5, Year 10" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Assign Teacher (Optional)</label>
                        <select class="form-select" id="teacher_id" name="teacher_id">
                            <option value="">No teacher assigned</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->subject }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">Class Capacity</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" 
                               value="30" min="1" max="50" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Class</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Teacher Modal -->
<div class="modal fade" id="assignTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Teacher to Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('manager.classes.assign-teacher') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="assign_class_id" name="class_ids[]">
                    <p>Assign a teacher to <strong id="assign_class_name"></strong>:</p>
                    <div class="mb-3">
                        <label for="assign_teacher_id" class="form-label">Select Teacher</label>
                        <select class="form-select" id="assign_teacher_id" name="teacher_id" required>
                            <option value="">Choose a teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->name }} ({{ $teacher->subject }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteClassName"></strong>?</p>
                <p class="text-danger">This action cannot be undone. All students will be unassigned from this class.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteClassForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Class</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function assignTeacher(classId, className) {
    document.getElementById('assign_class_id').value = classId;
    document.getElementById('assign_class_name').textContent = className;
    new bootstrap.Modal(document.getElementById('assignTeacherModal')).show();
}

function deleteClass(classId, className) {
    document.getElementById('deleteClassName').textContent = className;
    document.getElementById('deleteClassForm').action = `/manager/classes/${classId}`;
    new bootstrap.Modal(document.getElementById('deleteClassModal')).show();
}
</script>
@endsection
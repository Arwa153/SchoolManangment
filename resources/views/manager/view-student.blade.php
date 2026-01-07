@extends('layouts.dashboard')

@section('title', 'Student Profile')

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
    <h1 class="h3 mb-0">Student Profile</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.students.edit', $student->id) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Edit Student
        </a>
        <a href="{{ route('manager.students') }}" class="btn btn-secondary">
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
                <p class="text-muted mb-3">Student ID: {{ $student->id }}</p>
                
                <div class="mb-3">
                    <code class="bg-light px-3 py-2 rounded fs-6">{{ $student->student_code }}</code>
                    <button class="btn btn-sm btn-outline-secondary ms-2" 
                            onclick="copyToClipboard('{{ $student->student_code }}')" title="Copy Code">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $student->grades->count() }}</h6>
                            <small class="text-muted">Grades</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0">{{ $student->behaviorRecords->count() }}</h6>
                            <small class="text-muted">Records</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Gender</h6>
                    <span class="badge bg-{{ $student->gender == 'male' ? 'primary' : 'pink' }}">
                        {{ ucfirst($student->gender) }}
                    </span>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Date of Birth</h6>
                    <p class="mb-0">{{ $student->date_of_birth->format('F j, Y') }}</p>
                    <small class="text-muted">{{ $student->date_of_birth->age }} years old</small>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Class</h6>
                    @if($student->schoolClass)
                        <div>
                            <span class="badge bg-info">{{ $student->schoolClass->name }}</span>
                            <br><small class="text-muted">{{ $student->schoolClass->grade_level }}</small>
                            @if($student->schoolClass->teacher)
                                <br><small class="text-muted">Teacher: {{ $student->schoolClass->teacher->name }}</small>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </div>
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Parent</h6>
                    @if($student->parent)
                        <div>
                            <h6 class="mb-0">{{ $student->parent->name }}</h6>
                            <small class="text-muted">{{ $student->parent->email }}</small>
                        </div>
                    @else
                        <span class="text-muted">No parent registered</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Performance -->
    <div class="col-md-8">
        <!-- Grades -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Academic Grades</h5>
            </div>
            <div class="card-body">
                @if($student->grades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Semester</th>
                                    <th>Teacher</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->grades->sortByDesc('created_at') as $grade)
                                    <tr>
                                        <td>{{ $grade->subject }}</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }}">
                                                {{ $grade->grade }}%
                                            </span>
                                        </td>
                                        <td>{{ $grade->semester }}</td>
                                        <td>{{ $grade->teacher->name }}</td>
                                        <td>{{ $grade->created_at->format('M j, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($student->grades->count() > 0)
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">Average Grade</h6>
                                        <h4 class="mb-0 text-primary">{{ number_format($student->grades->avg('grade'), 1) }}%</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">Highest Grade</h6>
                                        <h4 class="mb-0 text-success">{{ $student->grades->max('grade') }}%</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">Total Grades</h6>
                                        <h4 class="mb-0 text-info">{{ $student->grades->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No grades recorded yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Behavior Records -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Behavior Records</h5>
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
                                            <span class="badge bg-{{ $record->type == 'positive' ? 'success' : 'warning' }}">
                                                {{ ucfirst($record->type) }}
                                            </span>
                                        </div>
                                        <p class="text-muted mb-2">{{ $record->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $record->teacher->name }}
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
                    </div>
                @endif
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
</script>
@endsection
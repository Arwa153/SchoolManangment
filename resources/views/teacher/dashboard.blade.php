@extends('layouts.dashboard')

@section('title', __('messages.teacher') . ' ' . __('messages.dashboard'))

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}" class="nav-link active">
    <i class="fas fa-tachometer-alt me-2"></i>
    {{ __('messages.dashboard') }}
</a>
<a href="{{ route('teacher.classes') }}" class="nav-link">
    <i class="fas fa-chalkboard me-2"></i>
    {{ __('messages.my_classes') }}
</a>
<a href="{{ route('teacher.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    {{ __('messages.all_students') }}
</a>
<a href="{{ route('teacher.timetable') }}" class="nav-link">
    <i class="fas fa-calendar-alt me-2"></i>
    {{ __('messages.my_timetable') }}
</a>
@endsection

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}! 👋</h1>
            <p class="page-subtitle">{{ __('messages.subject') }}: {{ auth()->user()->subject }} • {{ now()->format('F j, Y') }} - {{ now()->format('l') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="showQuickGradeModal()">
                <i class="fas fa-plus me-2"></i>
                Quick {{ __('messages.grade') }}
            </button>
            <button class="btn btn-outline-warning" onclick="showQuickBehaviorModal()">
                <i class="fas fa-clipboard-list me-2"></i>
                Quick Behavior
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['assigned_classes'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.assigned_classes') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-chalkboard fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.total_students') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['grades_given'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.grades_given') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['behavior_records'] }}</h3>
                        <p class="mb-0 opacity-75">{{ __('messages.behavior_records') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Today's Schedule -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.todays_schedule') }}</h5>
                <a href="{{ route('teacher.timetable') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view') }} Full Timetable</a>
            </div>
            <div class="card-body">
                @if($todayTimetable->count() > 0)
                    <div class="timeline">
                        @foreach($todayTimetable as $entry)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="me-3">
                                    <div class="bg-primary text-white rounded px-2 py-1 small">
                                        {{ $entry->start_time->format('H:i') }}
                                    </div>
                                    <div class="text-muted small text-center">
                                        {{ $entry->end_time->format('H:i') }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $entry->subject }}</h6>
                                    <p class="mb-0 text-muted">{{ $entry->schoolClass->name }}</p>
                                    @if($entry->notes)
                                        <small class="text-muted">{{ $entry->notes }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('messages.no_data') }}</p>
                        <a href="{{ route('teacher.timetable') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('messages.add') }} Schedule
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assigned Classes -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.my_classes') }}</h5>
                <a href="{{ route('teacher.classes') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view') }} {{ __('messages.classes') }}</a>
            </div>
            <div class="card-body">
                @if($assignedClasses->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($assignedClasses as $class)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $class->name }}</h6>
                                    <small class="text-muted">
                                        {{ __('messages.students') }}: {{ $class->students->count() }}/{{ $class->capacity }}
                                    </small>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('teacher.class.students', $class->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $class->students->count() }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('messages.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Grades -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('messages.recent_grades') }}</h5>
            </div>
            <div class="card-body">
                @if($recentGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.students') }}</th>
                                    <th>{{ __('messages.subject') }}</th>
                                    <th>{{ __('messages.grade') }}</th>
                                    <th>{{ __('messages.semester') }}</th>
                                    <th>{{ __('messages.incident_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentGrades as $grade)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($grade->student->name, 0, 1) }}
                                                </div>
                                                {{ $grade->student->name }}
                                            </div>
                                        </td>
                                        <td>{{ $grade->subject }}</td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade >= 70 ? 'success' : ($grade->grade >= 50 ? 'warning' : 'danger') }} rounded-pill">
                                                {{ $grade->grade }}%
                                            </span>
                                        </td>
                                        <td>{{ $grade->semester }}</td>
                                        <td>{{ $grade->created_at->format('M j, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('messages.no_data') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('messages.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('teacher.classes') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-chalkboard me-2"></i>
                            {{ __('messages.view') }} {{ __('messages.my_classes') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-success w-100 py-3" onclick="showQuickGradeModal()">
                            <i class="fas fa-plus-circle me-2"></i>
                            Quick {{ __('messages.add') }} {{ __('messages.grade') }}
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-warning w-100 py-3" onclick="showQuickBehaviorModal()">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Quick {{ __('messages.add') }} Behavior
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('teacher.timetable') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Manage Timetable
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showQuickGradeModal() {
    alert('Please go to "{{ __('messages.my_classes') }}" to select a class first, then add grades for specific students.');
}

function showQuickBehaviorModal() {
    alert('Please go to "{{ __('messages.my_classes') }}" to select a class first, then add behavior records for specific students.');
}
</script>
@endsection
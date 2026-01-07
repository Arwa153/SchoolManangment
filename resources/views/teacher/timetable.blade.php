@extends('layouts.dashboard')

@section('title', 'My Timetable')

@section('sidebar')
<a href="{{ route('teacher.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt me-2"></i>
    Dashboard
</a>
<a href="{{ route('teacher.classes') }}" class="nav-link">
    <i class="fas fa-chalkboard me-2"></i>
    My Classes
</a>
<a href="{{ route('teacher.students') }}" class="nav-link">
    <i class="fas fa-user-graduate me-2"></i>
    All Students
</a>
<a href="{{ route('teacher.timetable') }}" class="nav-link active">
    <i class="fas fa-calendar-alt me-2"></i>
    My Timetable
</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">My Timetable</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTimetableModal">
        <i class="fas fa-plus me-2"></i>
        Add Schedule Entry
    </button>
</div>

<!-- Weekly Timetable -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Weekly Schedule</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Time</th>
                        @foreach($days as $day)
                            <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $timeSlots = [];
                        foreach($weeklySchedule as $daySchedule) {
                            foreach($daySchedule as $entry) {
                                $timeKey = $entry->start_time->format('H:i') . '-' . $entry->end_time->format('H:i');
                                if (!in_array($timeKey, $timeSlots)) {
                                    $timeSlots[] = $timeKey;
                                }
                            }
                        }
                        sort($timeSlots);
                    @endphp

                    @if(count($timeSlots) > 0)
                        @foreach($timeSlots as $timeSlot)
                            @php
                                list($startTime, $endTime) = explode('-', $timeSlot);
                            @endphp
                            <tr>
                                <td class="fw-bold text-center bg-light">
                                    <div>{{ $startTime }}</div>
                                    <small class="text-muted">{{ $endTime }}</small>
                                </td>
                                @foreach($days as $day)
                                    <td class="text-center" style="height: 80px;">
                                        @php
                                            $dayEntries = $weeklySchedule->get($day, collect());
                                            $entry = $dayEntries->first(function($e) use ($startTime, $endTime) {
                                                return $e->start_time->format('H:i') === $startTime && 
                                                       $e->end_time->format('H:i') === $endTime;
                                            });
                                        @endphp
                                        
                                        @if($entry)
                                            <div class="bg-primary text-white rounded p-2 position-relative">
                                                <div class="fw-bold small">{{ $entry->subject }}</div>
                                                <div class="small">{{ $entry->schoolClass->name }}</div>
                                                @if($entry->notes)
                                                    <div class="small opacity-75">{{ \Illuminate\Support\Str::limit($entry->notes, 20) }}</div>
                                                @endif
                                                <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-1" 
                                                        onclick="deleteTimetableEntry({{ $entry->id }})" 
                                                        style="font-size: 10px; padding: 2px 4px;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($days) + 1 }}" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No schedule entries yet. Add your first class schedule!</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTimetableModal">
                                    <i class="fas fa-plus me-2"></i>
                                    Add First Entry
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Today's Schedule -->
@php
    $today = now()->format('l');
    $todaySchedule = $weeklySchedule->get($today, collect())->sortBy('start_time');
@endphp

@if($todaySchedule->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Today's Schedule ({{ $today }})</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($todaySchedule as $entry)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $entry->subject }}</h6>
                                    <span class="badge bg-primary">{{ $entry->time_range }}</span>
                                </div>
                                <p class="mb-1 text-muted">{{ $entry->schoolClass->name }}</p>
                                @if($entry->notes)
                                    <small class="text-muted">{{ $entry->notes }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Add Timetable Entry Modal -->
<div class="modal fade" id="addTimetableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Schedule Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('teacher.timetable.add') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">Select a class</option>
                            @foreach($assignedClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade_level }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="day" class="form-label">Day</label>
                        <select class="form-select" id="day" name="day" required>
                            <option value="">Select a day</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $day === now()->format('l') ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" 
                               value="{{ auth()->user()->subject }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="e.g., Bring textbooks, Lab session, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTimetableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Schedule Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this schedule entry?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteTimetableForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Entry</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteTimetableEntry(entryId) {
    document.getElementById('deleteTimetableForm').action = `/teacher/timetable/${entryId}`;
    new bootstrap.Modal(document.getElementById('deleteTimetableModal')).show();
}

// Validate time inputs
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeInput = document.getElementById('end_time');
    
    if (startTime) {
        // Set minimum end time to be after start time
        const [hours, minutes] = startTime.split(':');
        const minEndTime = String(parseInt(hours)).padStart(2, '0') + ':' + String(parseInt(minutes) + 30).padStart(2, '0');
        endTimeInput.min = minEndTime;
    }
});
</script>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\BehaviorRecord;
use App\Models\Timetable;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user();
        $assignedClasses = $teacher->assignedClasses()->with('students')->get();
        $totalStudents = $assignedClasses->sum(function($class) {
            return $class->students->count();
        });

        $recentGrades = Grade::where('teacher_id', $teacher->id)
            ->with('student')
            ->latest()
            ->take(5)
            ->get();

        $todayTimetable = Timetable::where('teacher_id', $teacher->id)
            ->where('day', now()->format('l'))
            ->with('schoolClass')
            ->orderBy('start_time')
            ->get();

        $stats = [
            'assigned_classes' => $assignedClasses->count(),
            'total_students' => $totalStudents,
            'grades_given' => Grade::where('teacher_id', $teacher->id)->count(),
            'behavior_records' => BehaviorRecord::where('teacher_id', $teacher->id)->count(),
        ];

        return view('teacher.dashboard', compact('assignedClasses', 'stats', 'recentGrades', 'todayTimetable'));
    }

    public function classes()
    {
        $teacher = auth()->user();
        $assignedClasses = $teacher->assignedClasses()->with('students.parent')->get();

        return view('teacher.classes', compact('assignedClasses'));
    }

    public function classStudents($classId)
    {
        $teacher = auth()->user();
        
        // Verify teacher is assigned to this class
        $class = $teacher->assignedClasses()->with('students.parent')->findOrFail($classId);
        
        return view('teacher.class-students', compact('class'));
    }

    public function students()
    {
        $teacher = auth()->user();
        $students = Student::whereHas('schoolClass.teachers', function($query) use ($teacher) {
            $query->where('users.id', $teacher->id);
        })->with('schoolClass', 'grades', 'behaviorRecords')->get();

        return view('teacher.students', compact('students'));
    }

    public function addGrade(Request $request)
    {
        $teacher = auth()->user();
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:255',
            'grade' => 'required|numeric|min:0|max:100',
            'semester' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Verify teacher is assigned to this class
        $teacher->assignedClasses()->findOrFail($request->class_id);
        
        // Verify student is in this class
        $student = Student::where('id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->firstOrFail();

        Grade::create([
            'student_id' => $request->student_id,
            'teacher_id' => $teacher->id,
            'subject' => $request->subject,
            'grade' => $request->grade,
            'semester' => $request->semester,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Grade added successfully!');
    }

    public function addBehaviorRecord(Request $request)
    {
        $teacher = auth()->user();
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:positive,negative',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_date' => 'required|date',
        ]);

        // Verify teacher is assigned to this class
        $teacher->assignedClasses()->findOrFail($request->class_id);
        
        // Verify student is in this class
        $student = Student::where('id', $request->student_id)
            ->where('class_id', $request->class_id)
            ->firstOrFail();

        BehaviorRecord::create([
            'student_id' => $request->student_id,
            'teacher_id' => $teacher->id,
            'class_id' => $request->class_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'incident_date' => $request->incident_date,
        ]);

        return redirect()->back()->with('success', 'Behavior record added successfully!');
    }

    public function editBehaviorRecord(Request $request, $id)
    {
        $teacher = auth()->user();
        
        $request->validate([
            'type' => 'required|in:positive,negative',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_date' => 'required|date',
        ]);

        // Find behavior record and verify teacher owns it
        $behaviorRecord = BehaviorRecord::where('teacher_id', $teacher->id)->findOrFail($id);
        
        // Verify teacher is still assigned to the class
        $teacher->assignedClasses()->findOrFail($behaviorRecord->class_id);

        $behaviorRecord->update([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'incident_date' => $request->incident_date,
        ]);

        return redirect()->back()->with('success', 'Behavior record updated successfully!');
    }

    public function deleteBehaviorRecord($id)
    {
        $teacher = auth()->user();
        
        // Find behavior record and verify teacher owns it
        $behaviorRecord = BehaviorRecord::where('teacher_id', $teacher->id)->findOrFail($id);
        
        // Verify teacher is still assigned to the class
        $teacher->assignedClasses()->findOrFail($behaviorRecord->class_id);
        
        $behaviorRecord->delete();

        return redirect()->back()->with('success', 'Behavior record deleted successfully!');
    }

    public function studentProfile($id)
    {
        $teacher = auth()->user();
        $student = Student::whereHas('schoolClass.teachers', function($query) use ($teacher) {
            $query->where('users.id', $teacher->id);
        })->with('schoolClass', 'grades', 'behaviorRecords')->findOrFail($id);

        return view('teacher.student-profile', compact('student'));
    }

    // ==================== TIMETABLE MANAGEMENT ====================
    public function timetable()
    {
        $teacher = auth()->user();
        $timetables = $teacher->timetables()->with('schoolClass')->get();
        $assignedClasses = $teacher->assignedClasses;
        
        // Group timetables by day
        $weeklySchedule = $timetables->groupBy('day');
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('teacher.timetable', compact('weeklySchedule', 'days', 'assignedClasses'));
    }

    public function addTimetableEntry(Request $request)
    {
        $teacher = auth()->user();
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subject' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Verify teacher is assigned to this class
        $teacher->assignedClasses()->findOrFail($request->class_id);

        // Check for time conflicts
        $conflict = Timetable::where('teacher_id', $teacher->id)
            ->where('day', $request->day)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()->withErrors(['time_conflict' => 'You have a scheduling conflict at this time.']);
        }

        Timetable::create([
            'teacher_id' => $teacher->id,
            'class_id' => $request->class_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'subject' => $request->subject,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Timetable entry added successfully!');
    }

    public function deleteTimetableEntry($id)
    {
        $teacher = auth()->user();
        $timetable = Timetable::where('teacher_id', $teacher->id)->findOrFail($id);
        $timetable->delete();

        return redirect()->back()->with('success', 'Timetable entry deleted successfully!');
    }

    // ==================== AJAX ENDPOINTS ====================
    public function getClassStudents($classId)
    {
        $teacher = auth()->user();
        
        // Verify teacher is assigned to this class
        $class = $teacher->assignedClasses()->findOrFail($classId);
        $students = $class->students()->select('id', 'name')->get();

        return response()->json($students);
    }
}

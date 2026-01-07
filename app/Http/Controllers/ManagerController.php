<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_students' => Student::count(),
            'total_classes' => SchoolClass::count(),
            'total_parents' => User::where('role', 'parent')->count(),
        ];

        $recentStudents = Student::with('schoolClass')->latest()->take(5)->get();
        $recentTeachers = User::where('role', 'teacher')->latest()->take(5)->get();

        return view('manager.dashboard', compact('stats', 'recentStudents', 'recentTeachers'));
    }

    // ==================== CLASSES MANAGEMENT ====================
    public function classes()
    {
        $classes = SchoolClass::with('teacher', 'students')->get();
        $teachers = User::where('role', 'teacher')->get();
        
        return view('manager.classes', compact('classes', 'teachers'));
    }

    public function createClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        SchoolClass::create($request->all());

        return redirect()->back()->with('success', 'Class created successfully!');
    }

    public function editClass($id)
    {
        $class = SchoolClass::with('teacher', 'students')->findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        
        return view('manager.edit-class', compact('class', 'teachers'));
    }

    public function updateClass(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        $class = SchoolClass::findOrFail($id);
        $class->update($request->all());

        return redirect()->route('manager.classes')->with('success', 'Class updated successfully!');
    }

    public function deleteClass($id)
    {
        $class = SchoolClass::findOrFail($id);
        
        // Unassign students from this class
        $class->students()->update(['class_id' => null]);
        
        $class->delete();

        return redirect()->back()->with('success', 'Class deleted successfully!');
    }

    public function viewClass($id)
    {
        $class = SchoolClass::with('teacher', 'students.parent')->findOrFail($id);
        
        return view('manager.view-class', compact('class'));
    }

    // ==================== TEACHERS MANAGEMENT ====================
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->with('assignedClasses')->get();
        
        return view('manager.teachers', compact('teachers'));
    }

    public function createTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'subject' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'subject' => $request->subject,
        ]);

        return redirect()->back()->with('success', 'Teacher created successfully!');
    }

    public function editTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->with('assignedClasses')->findOrFail($id);
        
        return view('manager.edit-teacher', compact('teacher'));
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'subject' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $teacher->update($updateData);

        return redirect()->route('manager.teachers')->with('success', 'Teacher updated successfully!');
    }

    public function deleteTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        // Unassign teacher from classes
        $teacher->assignedClasses()->update(['teacher_id' => null]);
        
        $teacher->delete();

        return redirect()->back()->with('success', 'Teacher deleted successfully!');
    }

    public function viewTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->with('assignedClasses.students')->findOrFail($id);
        
        return view('manager.view-teacher', compact('teacher'));
    }

    // ==================== STUDENTS MANAGEMENT ====================
    public function students()
    {
        $students = Student::with('schoolClass', 'parent')->get();
        $classes = SchoolClass::all();
        
        return view('manager.students', compact('students', 'classes'));
    }

    public function createStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_code' => 'required|string|max:20|unique:students,student_code',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'student_code' => strtoupper($request->student_code),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
        ]);

        return redirect()->back()->with('success', 'Student created successfully! Student Code: ' . $student->student_code);
    }

    public function editStudent($id)
    {
        $student = Student::with('schoolClass', 'parent')->findOrFail($id);
        $classes = SchoolClass::all();
        
        return view('manager.edit-student', compact('student', 'classes'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'student_code' => 'required|string|max:20|unique:students,student_code,' . $student->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $student->update([
            'name' => $request->name,
            'student_code' => strtoupper($request->student_code),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('manager.students')->with('success', 'Student updated successfully!');
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        
        // Delete related records
        $student->grades()->delete();
        $student->behaviorRecords()->delete();
        
        // Delete parent if exists
        if ($student->parent) {
            $student->parent->delete();
        }
        
        $student->delete();

        return redirect()->back()->with('success', 'Student deleted successfully!');
    }

    public function viewStudent($id)
    {
        $student = Student::with('schoolClass.teacher', 'parent', 'grades.teacher', 'behaviorRecords.teacher')->findOrFail($id);
        
        return view('manager.view-student', compact('student'));
    }

    public function assignStudentToClass(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->update(['class_id' => $request->class_id]);

        return redirect()->back()->with('success', 'Student assigned to class successfully!');
    }

    // ==================== ASSIGN TEACHER TO MULTIPLE CLASSES ====================
    public function assignTeacherToClass(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $teacher = User::where('role', 'teacher')->findOrFail($request->teacher_id);
        
        // Use syncWithoutDetaching to preserve existing assignments and add new ones
        $teacher->assignedClasses()->syncWithoutDetaching($request->class_ids);

        return redirect()->back()->with('success', 'Teacher assigned to classes successfully!');
    }

    public function showTeacherClassAssignment($teacherId)
    {
        $teacher = User::where('role', 'teacher')->with('assignedClasses')->findOrFail($teacherId);
        $allClasses = SchoolClass::all();
        
        return view('manager.assign-teacher-classes', compact('teacher', 'allClasses'));
    }

    // ==================== REMOVE TEACHER FROM CLASS ====================
    public function removeTeacherFromClass(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        $teacher = User::where('role', 'teacher')->findOrFail($request->teacher_id);
        $teacher->assignedClasses()->detach($request->class_id);

        return redirect()->back()->with('success', 'Teacher removed from class successfully!');
    }

    // ==================== REMOVE STUDENT FROM CLASS ====================
    public function removeStudentFromClass(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->update(['class_id' => null]);

        return redirect()->back()->with('success', 'Student removed from class successfully!');
    }
}

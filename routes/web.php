<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;

// LANGUAGE SWITCHING
Route::post('/language/switch', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

// PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/get-started', [HomeController::class, 'roleSelection'])->name('role.selection');

// AUTHENTICATION ROUTES (PUBLIC)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// REGISTRATION ROUTES (PUBLIC - CRITICAL!)
Route::get('/register/manager', [AuthController::class, 'showManagerRegister'])->name('register.manager');
Route::post('/register/manager', [AuthController::class, 'registerManager']);

Route::get('/register/teacher', [AuthController::class, 'showTeacherRegister'])->name('register.teacher');
Route::post('/register/teacher', [AuthController::class, 'registerTeacher']);

Route::get('/register/parent', [AuthController::class, 'showParentRegister'])->name('register.parent');

// PROTECTED ROUTES - MANAGER
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    
    // Classes Management
    Route::get('/classes', [ManagerController::class, 'classes'])->name('manager.classes');
    Route::post('/classes', [ManagerController::class, 'createClass'])->name('manager.classes.create');
    Route::get('/classes/{id}/edit', [ManagerController::class, 'editClass'])->name('manager.classes.edit');
    Route::put('/classes/{id}', [ManagerController::class, 'updateClass'])->name('manager.classes.update');
    Route::delete('/classes/{id}', [ManagerController::class, 'deleteClass'])->name('manager.classes.delete');
    Route::get('/classes/{id}/view', [ManagerController::class, 'viewClass'])->name('manager.classes.view');
    Route::post('/classes/assign-teacher', [ManagerController::class, 'assignTeacherToClass'])->name('manager.classes.assign-teacher');
    Route::get('/teachers/{id}/assign-classes', [ManagerController::class, 'showTeacherClassAssignment'])->name('manager.teachers.assign-classes');
    
    // Teachers Management
    Route::get('/teachers', [ManagerController::class, 'teachers'])->name('manager.teachers');
    Route::post('/teachers', [ManagerController::class, 'createTeacher'])->name('manager.teachers.create');
    Route::get('/teachers/{id}/edit', [ManagerController::class, 'editTeacher'])->name('manager.teachers.edit');
    Route::put('/teachers/{id}', [ManagerController::class, 'updateTeacher'])->name('manager.teachers.update');
    Route::delete('/teachers/{id}', [ManagerController::class, 'deleteTeacher'])->name('manager.teachers.delete');
    Route::get('/teachers/{id}/view', [ManagerController::class, 'viewTeacher'])->name('manager.teachers.view');
    Route::get('/teachers/{id}/assign-classes', [ManagerController::class, 'showTeacherClassAssignment'])->name('manager.teachers.assign-classes');
    Route::post('/classes/remove-teacher', [ManagerController::class, 'removeTeacherFromClass'])->name('manager.classes.remove-teacher');
    Route::post('/students/remove-from-class', [ManagerController::class, 'removeStudentFromClass'])->name('manager.students.remove-from-class');
    
    // Students Management
    Route::get('/students', [ManagerController::class, 'students'])->name('manager.students');
    Route::post('/students', [ManagerController::class, 'createStudent'])->name('manager.students.create');
    Route::get('/students/{id}/edit', [ManagerController::class, 'editStudent'])->name('manager.students.edit');
    Route::put('/students/{id}', [ManagerController::class, 'updateStudent'])->name('manager.students.update');
    Route::delete('/students/{id}', [ManagerController::class, 'deleteStudent'])->name('manager.students.delete');
    Route::get('/students/{id}/view', [ManagerController::class, 'viewStudent'])->name('manager.students.view');
    Route::post('/students/assign', [ManagerController::class, 'assignStudentToClass'])->name('manager.students.assign');
});

// PROTECTED ROUTES - TEACHER
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/classes', [TeacherController::class, 'classes'])->name('teacher.classes');
    Route::get('/classes/{id}/students', [TeacherController::class, 'classStudents'])->name('teacher.class.students');
    Route::get('/students', [TeacherController::class, 'students'])->name('teacher.students');
    Route::get('/students/{id}', [TeacherController::class, 'studentProfile'])->name('teacher.student.profile');
    
    // Grades and Behavior
    Route::post('/grades', [TeacherController::class, 'addGrade'])->name('teacher.grades.add');
    Route::post('/behaviors', [TeacherController::class, 'addBehaviorRecord'])->name('teacher.behaviors.add');
    Route::put('/behaviors/{id}', [TeacherController::class, 'editBehaviorRecord'])->name('teacher.behaviors.edit');
    Route::delete('/behaviors/{id}', [TeacherController::class, 'deleteBehaviorRecord'])->name('teacher.behaviors.delete');
    
    // Timetable Management
    Route::get('/timetable', [TeacherController::class, 'timetable'])->name('teacher.timetable');
    Route::post('/timetable', [TeacherController::class, 'addTimetableEntry'])->name('teacher.timetable.add');
    Route::delete('/timetable/{id}', [TeacherController::class, 'deleteTimetableEntry'])->name('teacher.timetable.delete');
    
    // AJAX Endpoints
    Route::get('/api/classes/{id}/students', [TeacherController::class, 'getClassStudents'])->name('teacher.api.class.students');
});

// PROTECTED ROUTES - PARENT
Route::middleware(['auth', 'role:parent'])->prefix('parent')->group(function () {
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/child/{id}', [ParentController::class, 'childProfile'])->name('parent.child.profile');
    Route::get('/grades', [ParentController::class, 'grades'])->name('parent.grades');
    Route::get('/behaviors', [ParentController::class, 'behaviors'])->name('parent.behaviors');
});

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Check if this is a parent login (student_code provided)
        if ($request->filled('student_code')) {
            return $this->handleParentLogin($request);
        }

        // Regular login for managers and teachers
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            
            // Redirect based on role
            switch ($user->role) {
                case 'manager':
                    return redirect('/manager/dashboard');
                case 'teacher':
                    return redirect('/teacher/dashboard');
                default:
                    Auth::logout();
                    return redirect('/login')->withErrors(['email' => 'Invalid role.']);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Handle parent login with student_code
    private function handleParentLogin(Request $request)
    {
        $request->validate([
            'student_code' => 'required|string',
        ]);

        // Find student by code
        $student = Student::where('student_code', $request->student_code)->first();
        
        if (!$student) {
            return back()->withErrors(['student_code' => 'Invalid student code.']);
        }

        // Check if parent account exists
        if ($student->parent_id) {
            // Parent exists, log them in
            $parent = User::find($student->parent_id);
            Auth::login($parent);
            return redirect('/parent/dashboard');
        } else {
            // Create parent account automatically
            $parent = User::create([
                'name' => $student->name . "'s Parent",
                'email' => 'parent_' . $student->student_code . '@school.com',
                'password' => Hash::make($request->student_code), // Use student_code as password
                'role' => 'parent',
            ]);

            // Link student to parent
            $student->update(['parent_id' => $parent->id]);

            Auth::login($parent);
            return redirect('/parent/dashboard');
        }
    }

    // Show manager registration
    public function showManagerRegister()
    {
        return view('auth.register-manager');
    }

    // Handle manager registration
    public function registerManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'manager',
        ]);

        Auth::login($user);
        return redirect('/manager/dashboard');
    }

    // Show teacher registration
    public function showTeacherRegister()
    {
        return view('auth.register-teacher');
    }

    // Handle teacher registration
    public function registerTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subject' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'subject' => $request->subject,
        ]);

        Auth::login($user);
        return redirect('/teacher/dashboard');
    }

    // Show parent registration (now just redirects to login)
    public function showParentRegister()
    {
        return view('auth.register-parent');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

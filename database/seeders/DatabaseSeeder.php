<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Grade;
use App\Models\BehaviorRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Manager
        $manager = User::create([
            'name' => 'John Manager',
            'email' => 'manager@school.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create Teachers
        $teacher1 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'subject' => 'Mathematics',
        ]);

        $teacher2 = User::create([
            'name' => 'Mike Wilson',
            'email' => 'mike@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'subject' => 'English Literature',
        ]);

        $teacher3 = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'subject' => 'Science',
        ]);

        // Create Classes and assign teachers (many-to-many)
        $class1 = SchoolClass::create([
            'name' => 'Class 5A',
            'grade_level' => 'Grade 5',
            'capacity' => 30,
        ]);

        $class2 = SchoolClass::create([
            'name' => 'Class 6B',
            'grade_level' => 'Grade 6',
            'capacity' => 25,
        ]);

        $class3 = SchoolClass::create([
            'name' => 'Class 7C',
            'grade_level' => 'Grade 7',
            'capacity' => 28,
        ]);

        // Assign teachers to multiple classes (many-to-many)
        $teacher1->assignedClasses()->attach([$class1->id, $class2->id]);
        $teacher2->assignedClasses()->attach([$class2->id, $class3->id]);
        $teacher3->assignedClasses()->attach([$class1->id, $class3->id]);

        // Create Students
        $students = [
            ['name' => 'Alice Smith', 'gender' => 'female', 'class_id' => $class1->id],
            ['name' => 'Bob Johnson', 'gender' => 'male', 'class_id' => $class1->id],
            ['name' => 'Charlie Brown', 'gender' => 'male', 'class_id' => $class1->id],
            ['name' => 'Diana Prince', 'gender' => 'female', 'class_id' => $class2->id],
            ['name' => 'Edward Norton', 'gender' => 'male', 'class_id' => $class2->id],
            ['name' => 'Fiona Green', 'gender' => 'female', 'class_id' => $class2->id],
            ['name' => 'George Miller', 'gender' => 'male', 'class_id' => $class3->id],
            ['name' => 'Hannah White', 'gender' => 'female', 'class_id' => $class3->id],
            ['name' => 'Ian Black', 'gender' => 'male', 'class_id' => $class3->id],
            ['name' => 'Julia Roberts', 'gender' => 'female', 'class_id' => $class1->id],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $student = Student::create([
                'name' => $studentData['name'],
                'student_code' => 'STU' . strtoupper(Str::random(6)),
                'class_id' => $studentData['class_id'],
                'date_of_birth' => now()->subYears(rand(10, 12))->subDays(rand(1, 365)),
                'gender' => $studentData['gender'],
            ]);
            $createdStudents[] = $student;
        }

        // Create Parent for first student (for testing parent registration)
        $parentStudent = $createdStudents[0]; // Alice Smith
        $parent = User::create([
            'name' => "Alice Smith's Parent",
            'email' => 'parent@school.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
        ]);
        $parentStudent->update(['parent_id' => $parent->id]);

        // Create some grades
        foreach ($createdStudents as $student) {
            // Math grades
            Grade::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher1->id,
                'subject' => 'Mathematics',
                'grade' => rand(60, 95),
                'semester' => 'First Semester',
                'notes' => 'Good progress in mathematics.',
            ]);

            // English grades
            Grade::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher2->id,
                'subject' => 'English Literature',
                'grade' => rand(65, 90),
                'semester' => 'First Semester',
                'notes' => 'Shows creativity in writing.',
            ]);

            // Science grades
            Grade::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher3->id,
                'subject' => 'Science',
                'grade' => rand(70, 98),
                'semester' => 'First Semester',
                'notes' => 'Excellent understanding of concepts.',
            ]);
        }

        // Create some behavior records
        foreach ($createdStudents as $student) {
            // Positive behavior
            BehaviorRecord::create([
                'student_id' => $student->id,
                'teacher_id' => $teacher1->id,
                'type' => 'positive',
                'title' => 'Excellent Participation',
                'description' => 'Student actively participated in class discussions and helped other students.',
                'incident_date' => now()->subDays(rand(1, 30)),
            ]);

            // Some students get negative behavior records
            if (rand(1, 3) == 1) {
                BehaviorRecord::create([
                    'student_id' => $student->id,
                    'teacher_id' => $teacher2->id,
                    'type' => 'negative',
                    'title' => 'Late to Class',
                    'description' => 'Student arrived 10 minutes late to class without a valid reason.',
                    'incident_date' => now()->subDays(rand(1, 15)),
                ]);
            }
        }

        // Create some timetable entries
        \App\Models\Timetable::create([
            'teacher_id' => $teacher1->id,
            'class_id' => $class1->id,
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'subject' => 'Mathematics',
            'notes' => 'Algebra basics',
        ]);

        \App\Models\Timetable::create([
            'teacher_id' => $teacher1->id,
            'class_id' => $class2->id,
            'day' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'subject' => 'Mathematics',
            'notes' => 'Geometry introduction',
        ]);

        // Teacher 2 timetable
        \App\Models\Timetable::create([
            'teacher_id' => $teacher2->id,
            'class_id' => $class2->id,
            'day' => 'Monday',
            'start_time' => '11:00',
            'end_time' => '12:00',
            'subject' => 'English Literature',
            'notes' => 'Poetry analysis',
        ]);

        \App\Models\Timetable::create([
            'teacher_id' => $teacher2->id,
            'class_id' => $class3->id,
            'day' => 'Wednesday',
            'start_time' => '14:00',
            'end_time' => '15:00',
            'subject' => 'English Literature',
            'notes' => 'Creative writing',
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Manager: manager@school.com / password');
        $this->command->info('Teacher: sarah@school.com / password');
        $this->command->info('Parent: parent@school.com / password');
        $this->command->info('Student Code for parent registration: ' . $createdStudents[1]->student_code);
    }
}

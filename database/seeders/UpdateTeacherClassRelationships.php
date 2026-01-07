<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;

class UpdateTeacherClassRelationships extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing teachers and classes
        $teachers = User::where('role', 'teacher')->get();
        $classes = SchoolClass::all();

        if ($teachers->count() > 0 && $classes->count() > 0) {
            // Clear existing relationships
            DB::table('class_teacher')->truncate();

            // Assign teachers to classes (many-to-many)
            foreach ($teachers as $teacher) {
                // Assign each teacher to 2 random classes
                $randomClasses = $classes->random(min(2, $classes->count()));
                foreach ($randomClasses as $class) {
                    DB::table('class_teacher')->insertOrIgnore([
                        'teacher_id' => $teacher->id,
                        'class_id' => $class->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Create sample timetable entries
            $teacher1 = $teachers->first();
            if ($teacher1) {
                $assignedClasses = $teacher1->assignedClasses;
                
                if ($assignedClasses->count() > 0) {
                    Timetable::create([
                        'teacher_id' => $teacher1->id,
                        'class_id' => $assignedClasses->first()->id,
                        'day' => 'Monday',
                        'start_time' => '09:00',
                        'end_time' => '10:00',
                        'subject' => $teacher1->subject,
                        'notes' => 'Morning session',
                    ]);

                    Timetable::create([
                        'teacher_id' => $teacher1->id,
                        'class_id' => $assignedClasses->first()->id,
                        'day' => 'Wednesday',
                        'start_time' => '11:00',
                        'end_time' => '12:00',
                        'subject' => $teacher1->subject,
                        'notes' => 'Mid-week session',
                    ]);
                }
            }

            $this->command->info('Teacher-class relationships updated successfully!');
            $this->command->info('Sample timetable entries created!');
        } else {
            $this->command->info('No teachers or classes found. Please run the main seeder first.');
        }
    }
}

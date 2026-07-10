<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::where('status', 'published')->get();

        if ($students->isEmpty() || $courses->isEmpty()) {
            return;
        }

        foreach ($students as $student) {
            // Enroll student to 1 or 2 random courses
            $enrolledCourses = $courses->random(min(2, $courses->count()));

            foreach ($enrolledCourses as $course) {
                Enrollment::create([
                    'user_id'     => $student->id,
                    'course_id'   => $course->id,
                    'enrolled_at' => now(),
                ]);

                // Get lessons for this course
                $lessons = $course->lessons;

                if ($lessons->count() > 0) {
                    // Mark some lessons as completed (e.g., first half)
                    $lessonsToComplete = $lessons->take(ceil($lessons->count() / 2));

                    foreach ($lessonsToComplete as $lesson) {
                        Progress::create([
                            'user_id'      => $student->id,
                            'lesson_id'    => $lesson->id,
                            'completed_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}

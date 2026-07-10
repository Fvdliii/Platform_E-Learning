<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollment::with('course.lessons')->get();

        if ($enrollments->isEmpty()) {
            return;
        }

        foreach ($enrollments as $enrollment) {
            $user = $enrollment->user;
            $course = $enrollment->course;
            
            $totalLessons = $course->lessons->count();
            
            if ($totalLessons > 0) {
                // Simulate some users having 100% progress
                // By marking all lessons of this course as completed for this user
                $isCompleted = (rand(1, 10) <= 4); // 40% chance of being fully completed
                
                if ($isCompleted) {
                    foreach ($course->lessons as $lesson) {
                        $user->progressRecords()->firstOrCreate([
                            'lesson_id' => $lesson->id,
                        ], [
                            'completed_at' => now(),
                        ]);
                    }

                    // Generate Certificate
                    Certificate::firstOrCreate([
                        'user_id'   => $user->id,
                        'course_id' => $course->id,
                    ], [
                        'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
                        'issued_at'          => now(),
                    ]);
                }
            }
        }
    }
}

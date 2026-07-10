<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enrollments = Enrollment::all();

        if ($enrollments->isEmpty()) {
            return;
        }

        $comments = [
            'Materi sangat bagus dan mudah dipahami.',
            'Penjelasannya jelas, tapi butuh lebih banyak contoh kasus.',
            'Luar biasa! Sangat membantu untuk pemula.',
            'Kursus ini membuka wawasan saya tentang topik ini.',
            'Cukup baik, instruktur menguasai materi.',
        ];

        foreach ($enrollments as $enrollment) {
            // Not every enrolled student leaves a review, let's say 70% chance
            if (rand(1, 10) <= 7) {
                Review::create([
                    'user_id'   => $enrollment->user_id,
                    'course_id' => $enrollment->course_id,
                    'rating'    => rand(4, 5), // Mostly positive reviews
                    'comment'   => $comments[array_rand($comments)],
                ]);
            }
        }
    }
}

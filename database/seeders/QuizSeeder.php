<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::where('status', 'published')->get();

        foreach ($courses as $course) {
            $quiz = Quiz::create([
                'course_id'     => $course->id,
                'title'         => 'Kuis Evaluasi: ' . $course->title,
                'description'   => 'Kuis ini dirancang untuk menguji pemahaman Anda setelah menyelesaikan materi kursus.',
                'passing_score' => 70,
            ]);

            // Tambahkan minimal 5 pertanyaan untuk kuis ini
            for ($i = 1; $i <= 5; $i++) {
                $question = $quiz->questions()->create([
                    'text' => "Pertanyaan contoh {$i} untuk modul " . $course->title . "?",
                ]);

                // Buat 4 jawaban per pertanyaan, acak jawaban benarnya
                $correctIndex = rand(1, 4);
                
                for ($j = 1; $j <= 4; $j++) {
                    $question->answers()->create([
                        'text'       => "Opsi Jawaban {$j}",
                        'is_correct' => ($j === $correctIndex),
                    ]);
                }
            }
        }
    }
}

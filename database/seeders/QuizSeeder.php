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

        $questionsPool = [
            'Apa arti dari "Watashi"?',
            'Bagaimana cara mengucapkan "Terima kasih" dalam bahasa Jepang?',
            'Partikel apa yang menunjukkan subjek kalimat?',
            'Apa arti "Gakusei"?',
            'Sebutkan sapaan selamat pagi dalam bahasa Jepang!',
        ];

        $answersPool = [
            ['Saya', 'Kamu', 'Dia', 'Mereka'],
            ['Arigatou', 'Sumimasen', 'Ohayou', 'Sayounara'],
            ['wa', 'o', 'ni', 'de'],
            ['Siswa', 'Guru', 'Dokter', 'Pegawai'],
            ['Ohayou gozaimasu', 'Konnichiwa', 'Konbanwa', 'Oyasuminasai'],
        ];

        foreach ($courses as $course) {
            $quiz = Quiz::create([
                'course_id'     => $course->id,
                'title'         => 'Kuis Evaluasi: ' . $course->title,
                'description'   => 'Kuis ini dirancang untuk menguji pemahaman Anda setelah menyelesaikan materi.',
                'passing_score' => 70,
            ]);

            for ($i = 0; $i < 5; $i++) {
                $question = $quiz->questions()->create([
                    'text' => $questionsPool[$i],
                ]);

                // Pilihan pertama dianggap benar untuk disederhanakan, lalu kita acak saat insert
                $answers = $answersPool[$i];
                $correctAnswerText = $answers[0];
                shuffle($answers);

                foreach ($answers as $ans) {
                    $question->answers()->create([
                        'text'       => $ans,
                        'is_correct' => ($ans === $correctAnswerText),
                    ]);
                }
            }
        }
    }
}

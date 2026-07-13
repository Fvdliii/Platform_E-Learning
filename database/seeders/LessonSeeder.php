<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first published course
        $course = Course::where('status', 'published')->first();

        if (!$course) {
            return;
        }

        $lessons = [
            [
                'course_id' => $course->id,
                'title'     => '1. Kosakata (Kotoba) Bab 1',
                'content'   => 'Mempelajari kosakata dasar seperti Watashi, Anata, Ano hito, -san, -jin, dll.',
                'type'      => 'text',
                'order'     => 1,
            ],
            [
                'course_id' => $course->id,
                'title'     => '2. Penjelasan Tata Bahasa (Bunpou)',
                'content'   => 'Simak video ini untuk memahami pola kalimat N1 wa N2 desu, dan N1 wa N2 ja arimasen.',
                'type'      => 'video',
                'file_path' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Dummy video link
                'order'     => 2,
            ],
            [
                'course_id' => $course->id,
                'title'     => '3. Pola Kalimat (Bukei) & Contoh Kalimat (Reibun)',
                'content'   => 'Gunakan referensi PDF ini untuk membaca contoh-contoh kalimat Bab 1.',
                'type'      => 'pdf',
                'file_path' => 'dummy/minna-b1.pdf',
                'order'     => 3,
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create($lessonData);
        }
    }
}

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
                'title'     => '1. Pengenalan Hiragana',
                'content'   => 'Materi ini membahas huruf-huruf dasar Hiragana, cara penulisan, dan pelafalan.',
                'type'      => 'text',
                'order'     => 1,
            ],
            [
                'course_id' => $course->id,
                'title'     => '2. Video Panduan Pelafalan',
                'content'   => 'Simak video ini untuk mengetahui cara melafalkan kata-kata dasar.',
                'type'      => 'video',
                'file_path' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Dummy video link
                'order'     => 2,
            ],
            [
                'course_id' => $course->id,
                'title'     => '3. Unduh Tabel Hiragana',
                'content'   => 'Gunakan tabel PDF ini sebagai referensi cepat.',
                'type'      => 'pdf',
                'file_path' => 'dummy/hiragana.pdf',
                'order'     => 3,
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create($lessonData);
        }
    }
}

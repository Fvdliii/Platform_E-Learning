<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = User::where('role', 'instructor')->first();

        if (!$instructor) {
            return;
        }

        $courses = [
            [
                'category'    => 'JLPT N5',
                'title'       => 'Pengantar Bahasa Jepang N5',
                'description' => 'Kursus ini dirancang untuk pemula absolut. Mempelajari hiragana, katakana, salam dasar, dan kosakata N5 paling umum.',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'JLPT N5',
                'title'       => 'Kosakata & Kanji Dasar N5',
                'description' => 'Menguasai 800 kosakata dan 103 kanji yang diujikan pada JLPT N5.',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'JLPT N4',
                'title'       => 'Tata Bahasa N4 Lengkap',
                'description' => 'Memahami seluruh pola kalimat (bunpou) yang diperlukan untuk lulus ujian JLPT N4.',
                'level'       => 'N4',
                'status'      => 'published',
            ],
            [
                'category'    => 'Kanji',
                'title'       => 'Belajar Kanji dari Nol',
                'description' => 'Pendekatan sistematis mempelajari kanji dimulai dari radikal dasar hingga membentuk kanji yang kompleks.',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'Grammar',
                'title'       => 'Grammar Jepang Menengah',
                'description' => 'Mendalami pola-pola kalimat tingkat menengah yang sering digunakan dalam percakapan sehari-hari.',
                'level'       => 'N3',
                'status'      => 'draft',
            ],
        ];

        foreach ($courses as $courseData) {
            $category = Category::where('name', $courseData['category'])->first();

            if (!$category) {
                continue;
            }

            if (!Course::where('title', $courseData['title'])->exists()) {
                Course::create([
                    'category_id'   => $category->id,
                    'instructor_id' => $instructor->id,
                    'title'         => $courseData['title'],
                    'description'   => $courseData['description'],
                    'level'         => $courseData['level'],
                    'status'        => $courseData['status'],
                ]);
            }
        }
    }
}

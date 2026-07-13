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
                'title'       => 'Minna no Nihongo Shokyu I - Bab 1',
                'description' => 'Mempelajari perkenalan diri, kewarganegaraan, profesi, dan pola kalimat dasar seperti "Watashi wa ~ desu".',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'JLPT N5',
                'title'       => 'Minna no Nihongo Shokyu I - Bab 2',
                'description' => 'Mempelajari kata tunjuk benda (Kore, Sore, Are, Kono, Sono, Ano) dan pola kalimat kepemilikan benda.',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'JLPT N5',
                'title'       => 'Minna no Nihongo Shokyu I - Bab 3',
                'description' => 'Mempelajari kata tunjuk tempat (Koko, Soko, Asoko, Kochira, Sochira, Achira) dan menyatakan tempat.',
                'level'       => 'N5',
                'status'      => 'published',
            ],
            [
                'category'    => 'JLPT N4',
                'title'       => 'Wagomu: Praktik Percakapan JLPT N4',
                'description' => 'Mempraktikkan percakapan sehari-hari dengan materi setara N4 bersama karakter Wagomu.',
                'level'       => 'N4',
                'status'      => 'published',
            ],
            [
                'category'    => 'Grammar',
                'title'       => 'Minna no Nihongo Shokyu II - Grammar N4',
                'description' => 'Mendalami tata bahasa buku Minna no Nihongo jilid kedua untuk persiapan level JLPT N4.',
                'level'       => 'N4',
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

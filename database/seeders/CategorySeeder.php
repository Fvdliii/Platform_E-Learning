<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'JLPT N5', 'description' => 'Level paling dasar. Belajar hiragana, katakana, dan kosakata/kanji dasar sehari-hari.'],
            ['name' => 'JLPT N4', 'description' => 'Level dasar lanjutan. Memahami bahasa Jepang dasar yang digunakan dalam situasi sehari-hari.'],
            ['name' => 'JLPT N3', 'description' => 'Level menengah. Memahami bahasa Jepang yang digunakan dalam situasi sehari-hari hingga taraf tertentu.'],
            ['name' => 'JLPT N2', 'description' => 'Level menengah atas. Memahami bahasa Jepang yang digunakan dalam situasi sehari-hari dan berbagai bidang.'],
            ['name' => 'JLPT N1', 'description' => 'Level tertinggi. Memahami bahasa Jepang yang digunakan dalam berbagai situasi.'],
            ['name' => 'Kanji', 'description' => 'Fokus khusus pada pembelajaran aksara Kanji dari tingkat dasar hingga lanjutan.'],
            ['name' => 'Grammar', 'description' => 'Fokus khusus pada tata bahasa (bunpou) Jepang dari tingkat dasar hingga lanjutan.'],
        ];

        foreach ($categories as $category) {
            if (!Category::where('name', $category['name'])->exists()) {
                Category::create($category);
            }
        }
    }
}

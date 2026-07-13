<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'Nihongo Gakuen',
            'copyright' => 'Fadli 2026',
            'login_title' => 'Portal Login Siswa & Instruktur',
            'keywords' => 'japanese, belajar bahasa jepang, e-learning jepang, jlpt, nihongo',
            'description' => 'Platform e-learning interaktif untuk menguasai bahasa Jepang dengan mudah dan menyenangkan.',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Sensei Yamada',
                'email' => 'instructor@gmail.com',
                'role' => 'instructor',
            ],
            [
                'name' => 'Siswa Teladan',
                'email' => 'student@gmail.com',
                'role' => 'student',
            ],
        ];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🧑‍💼 Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nama_lengkap' => 'Administrator',
                'email' => 'admin@santrifund.com',
                'no_telp' => '081234567890',
                'whatsapp' => '081234567890',
                'is_active' => true,
            ]
        );

        // 👳‍♂️ Ustadz Ahmad
        User::updateOrCreate(
            ['username' => 'ustadz_ahmad'],
            [
                'name' => 'Ustadz Ahmad Fauzi',
                'password' => Hash::make('ustadz123'),
                'role' => 'ustadz',
                'nama_lengkap' => 'Ustadz Ahmad Fauzi',
                'email' => 'ahmad@santrifund.com',
                'no_telp' => '081234567891',
                'whatsapp' => '081234567891',
                'is_active' => true,
            ]
        );

        // 👩‍🏫 Ustadzah Aisyah
        User::updateOrCreate(
            ['username' => 'ustadzah_aisyah'],
            [
                'name' => 'Ustadzah Aisyah',
                'password' => Hash::make('ustadz123'),
                'role' => 'ustadz',
                'nama_lengkap' => 'Ustadzah Aisyah',
                'email' => 'aisyah@santrifund.com',
                'no_telp' => '081234567892',
                'whatsapp' => '081234567892',
                'is_active' => true,
            ]
        );
    }
}

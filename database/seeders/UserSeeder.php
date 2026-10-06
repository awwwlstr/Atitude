<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'pepreguler26@attitude.com',
                'name' => 'Administrator',
                'password' => Hash::make('pep26reguler'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Pembuat Materi
        User::updateOrCreate(
            ['username' => 'pembuat2'],
            [
                'email' => 'rahma@attitude.com',
                'name' => 'Dra. Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'pembuat_materi',
                'status' => 'active',
            ]
        );

        // User (Siswa / Pelajar)
        User::updateOrCreate(
            ['username' => 'ahmad'],
            [
                'email' => 'ahmad@attitude.com',
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'siti'],
            [
                'email' => 'siti@attitude.com',
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'rizky'],
            [
                'email' => 'rizky@attitude.com',
                'name' => 'Rizky Pratama',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );
    }
}
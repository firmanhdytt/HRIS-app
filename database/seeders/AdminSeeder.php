<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin HRIS',
            'email' => 'admin@hris.com',
            'password' => Hash::make('password'), // Password default: 'password' (ubah di production)
            'role' => 'admin',
            'nik' => null, // Admin tidak perlu NIK
        ]);
    }
}
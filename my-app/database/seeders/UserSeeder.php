<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // Akun Admin
        // =====================================================================
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@mediva.com',
            'password' => Hash::make('rahasia123'),
            'role'     => 'admin',
        ]);

        // =====================================================================
        // Akun Bidan
        // =====================================================================
        User::create([
            'name'     => 'Bidan Mediva',
            'email'    => 'bidan@mediva.com',
            'password' => Hash::make('rahasia123'),
            'role'     => 'bidan',
        ]);

        // =====================================================================
        // Akun Dokter
        // =====================================================================
        User::create([
            'name'     => 'dr. Mediva',
            'email'    => 'dokter@mediva.com',
            'password' => Hash::make('rahasia123'),
            'role'     => 'dokter',
        ]);

        // =====================================================================
        // Akun Pasien (contoh)
        // =====================================================================
        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'pasien@mediva.com',
            'password' => Hash::make('rahasia123'),
            'role'     => 'pasien',
        ]);
    }
}
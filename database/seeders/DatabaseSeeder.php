<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KategoriMasalah;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'nama' => 'Admin IT',
            'email' => 'admin@hotelloccal.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'departemen' => 'IT Department',
            'create_at' => Carbon::now()
        ]);

        // Create regular user
        User::create([
            'nama' => 'Staff Front Office',
            'email' => 'staff@hotelloccal.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'departemen' => 'Front Office',
            'create_at' => Carbon::now()
        ]);

        // Create categories
        $kategori = [
            'Hardware (Komputer, Printer)',
            'Jaringan Internet',
            'Software & Aplikasi',
            'Email & Akun',
            'Telepon & Komunikasi',
            'Lainnya'
        ];

        foreach ($kategori as $kat) {
            KategoriMasalah::create([
                'nama_kategori' => $kat
            ]);
        }
    }
}
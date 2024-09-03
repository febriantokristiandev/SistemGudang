<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'John Doe',
            'email' => 'johndoe@example.com',
            'kata_sandi' => Hash::make('password123'),
            'alamat' => 'Jl. Merdeka',
            'nomor_telepon' => '081234567890',
            'tanggal_bergabung' => now(),
            'peran' => 'admin',
        ]);
    }
}

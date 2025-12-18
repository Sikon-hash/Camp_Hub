<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah email admin sudah ada untuk mencegah duplikasi
        $adminEmail = 'admin@camphub.com';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name'      => 'Chief Investigator', // Nama Admin
                'email'     => $adminEmail,
                'phone'     => '081234567890',       // Hapus baris ini jika tabel user tidak punya kolom phone
                'usertype'  => 'admin',              // PENTING: Sesuaikan dengan nama kolom di database Anda (role/is_admin/usertype)
                'password'  => Hash::make('password123'), // Password aman terenkripsi
            ]);
            
            $this->command->info('✅ Akun Admin berhasil dibuat! Silakan login.');
        } else {
            $this->command->warn('⚠️ Akun Admin sudah ada di database.');
        }
    }
}
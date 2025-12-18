<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. --- BUAT IZIN DAN PERAN TERLEBIH DAHULU ---
        Permission::create(['name' => 'view posts']);
        Permission::create(['name' => 'create posts']);
        
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        
        $adminRole->givePermissionTo(['view posts', 'create posts']);
        $userRole->givePermissionTo('view posts');

        // 2. --- BUAT PENGGUNA BARU YANG AKAN DIBERI PERAN ---
        
        // Membuat Admin User (Ini akan menjadi User ID 1)
        $adminUser = User::create([
             'name' => 'Admin System',
             'email' => 'admin@example.com',
             'password' => bcrypt('password'), // Wajib menggunakan bcrypt/Hash
        ]);
        
        // 3. --- MENETAPKAN PERAN (SEKARANG $adminUser BUKAN NULL) ---
        $adminUser->assignRole('admin'); // User ID 1 sekarang punya peran admin
        
        // Membuat Test User (Ini akan menjadi User ID 2)
        User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
        ])->assignRole('user'); // Memberi peran user langsung saat dibuat
        
        echo "Database Seeding Berhasil Dijalankan.\n";
    }
}

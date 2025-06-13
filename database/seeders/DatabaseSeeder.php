<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default roles for the application
        $roles = [
            'mahasiswa',
            'dosen',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $user = User::create([
            'name' => 'Ari',
            'nomor_induk' => '200121',
            'email' => 'a@a.com',
            'password' => '12345678',
        ]);

        $userMahasiswa = User::create([
            'name' => 'Adi',
            'nomor_induk' => '150121',
            'email' => 'b@b.com',
            'password' => '12345678',
        ]);

        $user->assignRole('dosen');
        $userMahasiswa->assignRole('mahasiswa');
    }
}

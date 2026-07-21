<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::create([
        'name' => 'miftah1',
        'username' => 'miftah1@gmail.com',
        'password' => Hash::make('miftah123'), // penting!
        'role' => 'guru',
    ]);

        User::create([
            'name' => 'admin',
            'username' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
            User::create([
            'name' => 'Muhammad Alfin Rido',
            'username' => 'alfin',
            'password' => Hash::make('12345678'),
            'role' => 'siswa',
        ]);
    }
    
}

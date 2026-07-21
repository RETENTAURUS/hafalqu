<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas; // Sesuaikan dengan nama model kamu

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => '6 ABDURRAHMAN', 'deskripsi' => 'Kelas 6'],
            ['nama' => '6 SAAD', 'deskripsi' => 'Kelas 6'],
            ['nama' => '5 UBAIDAH', 'deskripsi' => 'Kelas 5'],
            ['nama' => '5 SAID', 'deskripsi' => 'Kelas 5'],
            ['nama' => '4 ZAID', 'deskripsi' => 'Kelas 4'],
            ['nama' => '4 MUAZ', 'deskripsi' => 'Kelas 4'],
            ['nama' => '3 UMAR', 'deskripsi' => 'Kelas 3'],
            ['nama' => '3 ABU BAKAR', 'deskripsi' => 'Kelas 3'],
            ['nama' => '2 UTSMAN', 'deskripsi' => 'Kelas 2'],
            ['nama' => '2 ALI', 'deskripsi' => 'Kelas 2'],
            ['nama' => '1 THALHAH', 'deskripsi' => 'Kelas 1'],
            ['nama' => '1 ZUBAIR', 'deskripsi' => 'Kelas 1'],
        ];

        foreach ($data as $item) {
            Kelas::create($item);
        }
    }
}
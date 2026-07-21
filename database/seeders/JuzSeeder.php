<?php

namespace Database\Seeders;

use App\Models\Juz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JuzSeeder extends Seeder
{
    /**
     * Seed juz tertentu saja ke tabel `juz` (kolom: nomor): 1, 27, 28, 29, 30.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $nomorJuz = [1, 2, 28, 29, 30];

        $data = [];
        foreach ($nomorJuz as $nomor) {
            $data[] = [
                'nomor' => $nomor,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Juz::insert($data);
    }
}
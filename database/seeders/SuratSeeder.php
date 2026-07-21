<?php

namespace Database\Seeders;

use App\Models\Juz;
use App\Models\Surat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SuratSeeder extends Seeder
{
    /**
     * Daftar 37 surah Juz 30 (An-Naba - An-Nas).
     * - juz_id diambil dinamis dari tabel `juz` berdasarkan kolom `nomor` = 30.
     * - nomor_surat mengacu pada urutan surah di mushaf (78-114).
     */
    public function run(): void
    {
        $now = Carbon::now();

        $juzId = Juz::where('nomor', 30)->value('id');

        if (!$juzId) {
            throw new \RuntimeException('Juz 30 belum ada di tabel `juz`. Jalankan JuzSeeder terlebih dahulu.');
        }

        $data = [
            [
                'juz_id' => $juzId,
                'nomor_surat' => 78,
                'nama_surat' => 'An-Naba',
                'total_ayat' => 40,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 79,
                'nama_surat' => 'An-Naziat',
                'total_ayat' => 46,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 80,
                'nama_surat' => 'Abasa',
                'total_ayat' => 42,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 81,
                'nama_surat' => 'At-Takwir',
                'total_ayat' => 29,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 82,
                'nama_surat' => 'Al-Infitar',
                'total_ayat' => 19,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 83,
                'nama_surat' => 'Al-Mutaffifin',
                'total_ayat' => 36,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 84,
                'nama_surat' => 'Al-Inshiqaq',
                'total_ayat' => 25,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 85,
                'nama_surat' => 'Al-Buruj',
                'total_ayat' => 22,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 86,
                'nama_surat' => 'At-Tariq',
                'total_ayat' => 17,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 87,
                'nama_surat' => 'Al-Ala',
                'total_ayat' => 19,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 88,
                'nama_surat' => 'Al-Ghashiyah',
                'total_ayat' => 26,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 89,
                'nama_surat' => 'Al-Fajr',
                'total_ayat' => 30,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 90,
                'nama_surat' => 'Al-Balad',
                'total_ayat' => 20,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 91,
                'nama_surat' => 'Ash-Shams',
                'total_ayat' => 15,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 92,
                'nama_surat' => 'Al-Lail',
                'total_ayat' => 21,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 93,
                'nama_surat' => 'Ad-Duha',
                'total_ayat' => 11,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 94,
                'nama_surat' => 'Ash-Sharh',
                'total_ayat' => 8,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 95,
                'nama_surat' => 'At-Tin',
                'total_ayat' => 8,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 96,
                'nama_surat' => 'Al-Alaq',
                'total_ayat' => 19,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 97,
                'nama_surat' => 'Al-Qadr',
                'total_ayat' => 5,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 98,
                'nama_surat' => 'Al-Bayinah',
                'total_ayat' => 8,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 99,
                'nama_surat' => 'Az-Zalzalah',
                'total_ayat' => 8,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 100,
                'nama_surat' => 'Al-Adiyat',
                'total_ayat' => 11,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 101,
                'nama_surat' => 'Al-Qariah',
                'total_ayat' => 11,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 102,
                'nama_surat' => 'Al-Takathur',
                'total_ayat' => 8,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 103,
                'nama_surat' => 'Al-Asr',
                'total_ayat' => 3,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 104,
                'nama_surat' => 'Al-Humazah',
                'total_ayat' => 9,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 105,
                'nama_surat' => 'Al-Fil',
                'total_ayat' => 5,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 106,
                'nama_surat' => 'Quraish',
                'total_ayat' => 4,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 107,
                'nama_surat' => 'Al-Ma\'un',
                'total_ayat' => 7,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 108,
                'nama_surat' => 'Al-Kauthar',
                'total_ayat' => 3,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 109,
                'nama_surat' => 'Al-Kafirun',
                'total_ayat' => 6,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 110,
                'nama_surat' => 'An-Nasr',
                'total_ayat' => 3,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 111,
                'nama_surat' => 'Al-Masad',
                'total_ayat' => 5,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 112,
                'nama_surat' => 'Al-Ikhlas',
                'total_ayat' => 4,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 113,
                'nama_surat' => 'Al-Falaq',
                'total_ayat' => 5,
            ],
            [
                'juz_id' => $juzId,
                'nomor_surat' => 114,
                'nama_surat' => 'An-Nas',
                'total_ayat' => 6,
            ],
        ];

        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        unset($row);

        Surat::insert($data);
    }
}
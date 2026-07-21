<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Juz;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Pemula Qur\'ani',
                'description' => 'Menyelesaikan 5 surat',
                'icon' => '🌱',
                'image' => 'Pemula.png',
                'level' => 'bronze',
                'criteria_type' => 'hafalan',
                'criteria_value' => 5,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Penjelajah Juz 30',
                'description' => 'Menyelesaikan Juz 30',
                'icon' => '📖',
                'image' => 'juz30.png',
                'level' => 'bronze',
                'criteria_type' => 'juz_selesai',
                'criteria_value' => 1,
                'juz_nomor' => 30,
            ],
            [
                'name' => 'Penakluk Juz 29',
                'description' => 'Menyelesaikan Juz 29',
                'icon' => '🌙',
                'image' => 'juz29.png',
                'level' => 'silver',
                'criteria_type' => 'juz_selesai',
                'criteria_value' => 1,
                'juz_nomor' => 29,
            ],
            [
                'name' => 'Penakluk Juz 28',
                'description' => 'Menyelesaikan Juz 28',
                'icon' => '⭐',
                'image' => 'juz28.png',
                'level' => 'silver',
                'criteria_type' => 'juz_selesai',
                'criteria_value' => 1,
                'juz_nomor' => 28,
            ],
            [
                'name' => 'Penjelajah Juz 1',
                'description' => 'Menyelesaikan Juz 1',
                'icon' => '🕌',
                'image' => 'juz1.png',
                'level' => 'gold',
                'criteria_type' => 'juz_selesai',
                'criteria_value' => 1,
                'juz_nomor' => 1,
            ],
            [
                'name' => 'Penjelajah Juz 2',
                'description' => 'Menyelesaikan Juz 2',
                'icon' => '👑',
                'image' => 'juz2.png',
                'level' => 'gold',
                'criteria_type' => 'juz_selesai',
                'criteria_value' => 1,
                'juz_nomor' => 2,
            ],
            [
                'name' => 'Raja Quiz',
                'description' => 'Peringkat 1 leaderboard',
                'icon' => '👑',
                'image' => 'rajaQuiz.png',
                'level' => 'platinum',
                'criteria_type' => 'leaderboard_rank',
                'criteria_value' => 1,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Nilai Sempurna',
                'description' => 'Skor 100',
                'icon' => '⭐',
                'image' => 'nilai100.png',
                'level' => 'gold',
                'criteria_type' => 'nilai_sempurna',
                'criteria_value' => 100,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Hafizh Cilik',
                'description' => 'Menyelesaikan seluruh target (30, 29, 28, 1, 2)',
                'icon' => '💎',
                'image' => 'hafizhCilik.png',
                'level' => 'platinum',
                'criteria_type' => 'juz_selesai',
                // Meta badge: not tied to one juz_id, criteria_value = jumlah juz target yang harus selesai.
                'criteria_value' => 5,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Semangat 3 Hari',
                'description' => 'Login 3 hari berturut-turut',
                'icon' => '🌞',
                // ASUMSI: nama file generik dari upload form (badge_<timestamp>.png).
                // Ini yang timestamp-nya paling awal di antara 2 file generik yang ada.
                // Tolong konfirmasi/ganti kalau ini bukan gambar "Semangat 3 Hari".
                'image' => 'badge_1782810338.png',
                'level' => 'bronze',
                'criteria_type' => 'login_streak',
                'criteria_value' => 3,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Konsisten 7 Hari',
                'description' => 'Login 7 hari',
                'icon' => '🌤',
                'image' => '7hari.png',
                'level' => 'silver',
                'criteria_type' => 'login_streak',
                'criteria_value' => 7,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Istiqamah 14 Hari',
                'description' => 'Login 14 hari',
                'icon' => '🌈',
                'image' => '14hari.png',
                'level' => 'gold',
                'criteria_type' => 'login_streak',
                'criteria_value' => 14,
                'juz_nomor' => null,
            ],
            [
                'name' => 'Bintang Istiqamah',
                'description' => 'Login 30 hari',
                'icon' => '🌟',
                'image' => '30hari.png',
                'level' => 'platinum',
                'criteria_type' => 'login_streak',
                'criteria_value' => 30,
                'juz_nomor' => null,
            ],
        ];

        foreach ($badges as $badge) {
            $juzId = null;

            if ($badge['juz_nomor'] !== null) {
                $juz = Juz::where('nomor', $badge['juz_nomor'])->first();
                $juzId = $juz->id ?? null;

                if (!$juzId) {
                    $this->command?->warn("Juz nomor {$badge['juz_nomor']} tidak ditemukan, badge '{$badge['name']}' dibuat tanpa juz_id.");
                }
            }

            // Peringatan kalau file gambar yang direferensikan ternyata tidak ada di storage
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('badges/' . $badge['image'])) {
                $this->command?->warn("File gambar 'badges/{$badge['image']}' tidak ditemukan di storage untuk badge '{$badge['name']}'.");
            }

            Badge::updateOrCreate(
                ['name' => $badge['name']], // hindari duplikat jika seeder dijalankan ulang
                [
                    'description' => $badge['description'],
                    'icon' => $badge['icon'],
                    'image' => $badge['image'],
                    'level' => $badge['level'],
                    'criteria_type' => $badge['criteria_type'],
                    'criteria_value' => $badge['criteria_value'],
                    'juz_id' => $juzId,
                    'quiz_id' => null,
                    'surat_id' => null,
                    'is_active' => true,
                    'required_points' => 0,
                ]
            );
        }

        $this->command?->info('13 lencana berhasil di-seed.');
    }
}
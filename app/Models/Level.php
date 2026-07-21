<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name', 'min_points', 'max_points'];

    // ── Cari level berdasarkan poin ───────────────
    public static function findByPoints(int $points): ?self
    {
        return self::where('min_points', '<=', $points)
                   ->where('max_points', '>=', $points)
                   ->orderByDesc('min_points')
                   ->first();
    }

    // ── Cari level berikutnya ─────────────────────
    public static function findNext(int $points): ?self
    {
        return self::where('min_points', '>', $points)
                   ->orderBy('min_points')
                   ->first();
    }

    // ── Semua data progress level ─────────────────
    public static function progressData(int $points): array
    {
        $current = self::findByPoints($points);
        $next    = self::findNext($points);

        if (!$current && !$next) {
            return [
                'persen'        => 0,
                'poin_sekarang' => $points,
                'poin_target'   => 100,
                'label_current' => 'Pemula',
                'label_next'    => 'Level Berikutnya',
                'sisa'          => max(0, 100 - $points),
                'is_max'        => false,
            ];
        }

        if ($current && !$next) {
            return [
                'persen'        => 100,
                'poin_sekarang' => $points,
                'poin_target'   => $current->max_points,
                'label_current' => $current->name,
                'label_next'    => 'Level Tertinggi',
                'sisa'          => 0,
                'is_max'        => true,
            ];
        }

        $base   = $current ? $current->min_points : 0;
        $target = $next->min_points;
        $range  = max($target - $base, 1);
        $gained = $points - $base;
        $persen = min(round(($gained / $range) * 100), 100);

        return [
            'persen'        => $persen,
            'poin_sekarang' => $points,
            'poin_target'   => $target,
            'label_current' => $current ? $current->name : 'Pemula',
            'label_next'    => $next->name,
            'sisa'          => max(0, $target - $points),
            'is_max'        => false,
        ];
    }

    // ── Cek apakah poin baru menyebabkan naik level ─
    // Bandingkan level sebelum dan sesudah tambah poin
    public static function checkLevelUp(int $pointsBefore, int $pointsAfter): ?self
    {
        $levelBefore = self::findByPoints($pointsBefore);
        $levelAfter  = self::findByPoints($pointsAfter);

        if (!$levelAfter) return null;

        // Naik level jika level berubah
        if (!$levelBefore || $levelBefore->id !== $levelAfter->id) {
            return $levelAfter;
        }

        return null;
    }
}

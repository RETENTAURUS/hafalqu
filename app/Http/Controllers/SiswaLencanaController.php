<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\UserHafalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaLencanaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allBadges = Badge::where('is_active', true)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $earnedBadgeIds = UserBadge::where('user_id', $user->id)
            ->pluck('badge_id')
            ->toArray();

        $earnedBadges = $allBadges->filter(fn($badge) => in_array($badge->id, $earnedBadgeIds))->values();
        $lockedBadges = $allBadges->filter(fn($badge) => !in_array($badge->id, $earnedBadgeIds))->values();

        $lockedBadgesWithProgress = $lockedBadges->map(function ($badge) use ($user) {
            $progress = $this->calculateProgress($user, $badge);
            $badge->progress         = $progress['percent'];
            $badge->progress_current = $progress['current'];
            $badge->progress_target  = $progress['target'];
            $badge->progress_text    = $progress['text'];
            $badge->is_claimable     = $progress['percent'] >= 100;
            return $badge;
        });

        // Auto-award lencana yang sudah memenuhi syarat
        $this->autoAwardBadges($user, $lockedBadgesWithProgress);

        return view('siswa.lencana.index', compact(
            'earnedBadges',
            'lockedBadgesWithProgress',
            'allBadges'
        ));
    }

    /**
     * Auto-award badge jika progress sudah 100%
     */
    private function autoAwardBadges($user, $lockedBadges): void
    {
        foreach ($lockedBadges as $badge) {
            if ($badge->is_claimable) {
                $created = UserBadge::firstOrCreate([
                    'user_id'  => $user->id,
                    'badge_id' => $badge->id,
                ], [
                    'earned_at' => now(),
                ]);

                // Bonus poin saat badge baru saja diraih (samakan perilaku dengan
                // SiswaQuizController::checkAndAwardBadges agar tidak ada badge
                // yang "bocor" tanpa bonus poin tergantung dari mana ia dipicu).
                if ($created->wasRecentlyCreated && $badge->required_points > 0) {
                    $user->increment('points', $badge->required_points);
                    \App\Models\PoinLog::create([
                        'user_id' => $user->id,
                        'sumber'  => 'Lencana: ' . $badge->name,
                        'poin'    => $badge->required_points,
                    ]);
                }
            }
        }
    }

    /**
     * Hitung progress lengkap (percent, current, target, text)
     */
    private function calculateProgress($user, $badge): array
    {
        $type   = $badge->criteria_type;
        $target = (int) $badge->criteria_value;

        // Tangani target tidak valid
        if ($target <= 0) {
            return $this->buildProgress(0, 0, 'Tidak ada target');
        }

        switch ($type) {

            // ── POIN ──────────────────────────────────────────────────────────
            case 'poin':
                /*
                 * Lencana bisa terikat ke:
                 *  - quiz_id  → poin dari quiz tertentu saja
                 *  - (kosong) → total poin user
                 */
                $current = $badge->quiz_id
                    ? $this->pointsFromQuiz($user, $badge->quiz_id)
                    : ($user->points ?? 0);

                return $this->buildProgress($current, $target, $current . '/' . $target . ' poin');

            // ── QUIZ SELESAI ───────────────────────────────────────────────────
            case 'quiz_selesai':
                $query = $user->quizAttempts()
                    ->whereNotNull('finished_at')
                    ->where('score', '>=', 70);

                if ($badge->quiz_id) {
                    $current = $query->where('quiz_id', $badge->quiz_id)->exists() ? 1 : 0;
                    $label   = $current . '/1 quiz spesifik selesai';
                } else {
                    $current = $query->distinct('quiz_id')->count('quiz_id');
                    $label   = $current . '/' . $target . ' quiz selesai';
                }

                return $this->buildProgress($current, $target, $label);

            // ── NILAI SEMPURNA ─────────────────────────────────────────────────
            case 'nilai_sempurna':
                $query = $user->quizAttempts()
                    ->whereNotNull('finished_at')
                    ->where('score', 100);

                if ($badge->quiz_id) {
                    $current = $query->where('quiz_id', $badge->quiz_id)->exists() ? 1 : 0;
                    $label   = $current . '/1 nilai sempurna di quiz ini';
                } else {
                    $current = $query->count();
                    $label   = $current . '/' . $target . ' nilai sempurna';
                }

                return $this->buildProgress($current, $target, $label);

            // ── HAFALAN (ayat / surat) ─────────────────────────────────────────
            case 'hafalan':
                /*
                 * Menghitung jumlah surat yang sudah berstatus "hafal" secara manual
                 * (via user_hafalans). Kalau di sistemmu progres surat juga sebenarnya
                 * digerakkan lewat quiz, beri tahu saya supaya disamakan seperti
                 * juz_selesai di bawah.
                 */
                $query = UserHafalan::where('user_id', $user->id)
                    ->where('status', 'hafal');

                if ($badge->surat_id) {
                    $current = $query->where('surat_id', $badge->surat_id)->exists() ? 1 : 0;
                    $suratName = $badge->surat->nama_surat ?? 'surat ini';
                    $label   = $current === 1
                        ? "Sudah hafal {$suratName}"
                        : "Belum hafal {$suratName}";
                } else {
                    $current = $query->count();
                    $label   = $current . '/' . $target . ' surat dihafal';
                }

                return $this->buildProgress($current, $target, $label);

            // ── JUZ SELESAI ────────────────────────────────────────────────────
            case 'juz_selesai':
                /*
                 * Sumber kebenaran sekarang adalah quizzes.juz_id (bukan lagi
                 * badge.quiz_id yang dipilih manual oleh guru). Sistem otomatis
                 * mencari SEMUA quiz aktif yang juz_id-nya cocok, lalu juz
                 * dianggap selesai kalau siswa sudah dapat nilai 100 di
                 * SEMUA quiz tersebut (mendukung 1 quiz per juz maupun
                 * beberapa quiz per juz, mis. dipecah per surat).
                 */
                if ($badge->juz_id) {
                    return $this->progressForJuz($user, $badge->juz_id);
                }

                // Tanpa juz_id -> meta badge (mis. "Hafizh Cilik"): dihitung dari
                // badge juz_selesai lain yang SUDAH diraih user.
                $completedJuz = $this->countCompletedJuzBadges($user);
                $label = $completedJuz . '/' . $target . ' juz selesai';
                return $this->buildProgress($completedJuz, $target, $label);

            default:
                return $this->buildProgress(0, $target, 'Kriteria tidak dikenali');
        }
    }

    // ── HELPER: Poin dari quiz tertentu ───────────────────────────────────────

    private function pointsFromQuiz($user, int $quizId): int
    {
        $best = $user->quizAttempts()
            ->where('quiz_id', $quizId)
            ->whereNotNull('finished_at')
            ->orderByDesc('score')
            ->first();

        return $best ? (int) $best->score : 0;
    }

    // ── HELPER: Hitung progress juz berdasarkan quiz yang juz_id-nya cocok ────

    /**
     * Cari semua quiz aktif dengan quizzes.juz_id = $juzId, lalu hitung berapa
     * yang sudah diselesaikan siswa dengan nilai 100. Juz dianggap selesai
     * kalau SEMUA quiz untuk juz itu sudah sempurna.
     */
    private function progressForJuz($user, int $juzId): array
    {
        $quizIds = \App\Models\Quiz::where('juz_id', $juzId)
            ->where('is_active', true)
            ->pluck('id');

        if ($quizIds->isEmpty()) {
            return $this->buildProgress(0, 1, 'Belum ada quiz untuk juz ini');
        }

        $completedCount = $user->quizAttempts()
            ->whereIn('quiz_id', $quizIds)
            ->whereNotNull('finished_at')
            ->where('score', 100)
            ->distinct('quiz_id')
            ->count('quiz_id');

        $totalQuiz = $quizIds->count();
        $label = $completedCount . '/' . $totalQuiz . ' quiz juz ini selesai sempurna';

        // current/target dibuat proporsional ke jumlah quiz supaya progress bar
        // bertahap kalau quiz-nya lebih dari satu, tapi tetap butuh 100% (semua
        // quiz selesai) untuk badge-nya diberikan.
        return $this->buildProgress($completedCount, $totalQuiz, $label);
    }

    // ── HELPER: Hitung berapa badge "juz_selesai" yang sudah diraih user ──────

    /**
     * Dipakai untuk meta badge seperti "Hafizh Cilik": menghitung dari badge
     * juz_selesai lain yang SUDAH diraih user (via UserBadge), bukan menghitung
     * ulang dari data hafalan mentah. Ini otomatis konsisten baik juz itu
     * diraih lewat quiz maupun lewat hafalan manual.
     */
    private function countCompletedJuzBadges($user): int
    {
        $earnedBadgeIds = UserBadge::where('user_id', $user->id)->pluck('badge_id');

        return Badge::whereIn('id', $earnedBadgeIds)
            ->where('criteria_type', 'juz_selesai')
            ->whereNotNull('juz_id')
            ->distinct('juz_id')
            ->count('juz_id');
    }

    // ── HELPER: Bangun array progress ─────────────────────────────────────────

    private function buildProgress(int $current, int $target, string $text): array
    {
        $percent = $target > 0
            ? (int) min(100, round(($current / $target) * 100))
            : 100;

        return [
            'current' => $current,
            'target'  => $target,
            'percent' => $percent,
            'text'    => $text,
        ];
    }
}
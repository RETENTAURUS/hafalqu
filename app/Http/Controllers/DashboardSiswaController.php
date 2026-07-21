<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Level;
use App\Models\Badge;
use App\Models\PoinLog;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $totalPoin = $user->points ?? 0;
        $kelasId   = $user->kelas_id;

        // ── Level & Progress ───────────────────────────
        $levelData     = Level::progressData($totalPoin);
        $levelNama     = $levelData['label_current'];
        $progress      = $levelData['persen'];
        $poinTarget    = $levelData['poin_target'];
        $labelNextLevel = $levelData['label_next'];
        $sisaPoin      = $levelData['sisa'];
        $isMaxLevel    = $levelData['is_max'];

        // Semua level untuk step indicator
        $semuaLevel = Level::orderBy('min_points')->get();

        // ── Lencana ────────────────────────────────────
        $lencanaDiraih = $user->badges()->count();
        $totalLencana  = Badge::count();

        // ── Peringkat & Total Siswa ────────────────────
        $totalSiswa = 0;
        $peringkat  = '-';

        if ($kelasId) {
            $totalSiswa = User::where('role', 'siswa')
                              ->where('kelas_id', $kelasId)
                              ->count();

            $peringkat = User::where('role', 'siswa')
                             ->where('kelas_id', $kelasId)
                             ->where('points', '>', $totalPoin)
                             ->count() + 1;
        }

        // ── Quiz tersedia (dengan bobot_poin) ──────────
        $quizzes = Quiz::where('is_active', true)
                       ->with('soals')
                       ->orderBy('order')
                       ->orderBy('created_at')
                       ->take(5)
                       ->get();

        return view('siswa.dashboard', compact(
            'user', 'totalPoin',
            'levelNama', 'progress', 'poinTarget', 'labelNextLevel', 'sisaPoin', 'isMaxLevel',
            'semuaLevel',
            'lencanaDiraih', 'totalLencana',
            'peringkat', 'totalSiswa',
            'quizzes'
        ));
    }

    public function leaderboard()
    {
        $user  = Auth::user();
        $kelas = $user->kelas;

        if (!$kelas) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Kamu belum terdaftar di kelas manapun.');
        }

        $leaderboard = User::where('role', 'siswa')
            ->where('kelas_id', $kelas->id)
            ->orderByDesc('points')
            ->take(10)
            ->get();

        $myRank = $leaderboard->search(fn($s) => $s->id === $user->id);
        $myRank = $myRank !== false ? $myRank + 1 : null;

        $riwayatPoin = PoinLog::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        return view('siswa.leaderboard', compact(
            'kelas', 'leaderboard', 'myRank', 'riwayatPoin'
        ));
    }
}

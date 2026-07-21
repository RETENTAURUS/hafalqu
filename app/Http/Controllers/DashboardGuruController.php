<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user();

        $kelasId = $guru->kelas_id;

        // Leaderboard hanya siswa dalam kelas guru
        $leaderboard = User::where('role', 'siswa')
            ->where('kelas_id', $kelasId)
            ->withSum('quizAttempts', 'score')
            ->orderByDesc('quiz_attempts_sum_score')
            ->take(6)
            ->get()
            ->map(function ($user) {
                $user->points = $user->quiz_attempts_sum_score ?? 0;
                return $user;
            });

        // Total siswa kelas guru
        $totalSiswa = User::where('role', 'siswa')
            ->where('kelas_id', $kelasId)
            ->count();

        // Rata-rata skor siswa kelas guru
        $rataRataSkor = QuizAttempt::whereHas('user', function ($query) use ($kelasId) {
                $query->where('role', 'siswa')
                      ->where('kelas_id', $kelasId);
            })
            ->avg('score');

        // Total lencana siswa kelas guru
        $totalLencanaDiberikan = DB::table('user_badges')
            ->join('users', 'user_badges.user_id', '=', 'users.id')
            ->where('users.kelas_id', $kelasId)
            ->count();

        return view('guru.dashboard', [
            'namaGuru' => $guru->name,
            'peranGuru' => 'Guru',
            'tanggal' => now()->translatedFormat('d F Y'),

            'totalSiswa' => $totalSiswa,
            'quizAktif' => Quiz::where('is_active', true)->count(),
            'rataRataSkor' => round($rataRataSkor ?? 0, 2),
            'totalLencanaDiberikan' => $totalLencanaDiberikan,
            'leaderboard' => $leaderboard,
            'lencanaList' => Badge::all()
        ]);
    }
}
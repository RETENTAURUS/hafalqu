<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruLeaderboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user();

        // Ambil kelas yang diampu guru (asumsi satu kelas)
        $kelas = $guru->kelas;

        // Inisialisasi variabel dengan nilai default
        $leaderboard   = collect();
        $totalSiswa    = 0;
        $avgPoints     = 0;
        $highestPoints = 0;

        if ($kelas) {
            // Ambil semua siswa di kelas tersebut, urutkan berdasarkan poin tertinggi
            $leaderboard = User::where('role', 'siswa')
                ->where('kelas_id', $kelas->id)
                ->orderByDesc('points')
                ->get();

            $totalSiswa   = $leaderboard->count();
            $avgPoints    = $totalSiswa > 0 ? round($leaderboard->avg('points')) : 0;
            $highestPoints = $leaderboard->first()?->points ?? 0;
        }

        return view('guru.leaderboard.index', compact(
            'kelas',
            'leaderboard',
            'totalSiswa',
            'avgPoints',
            'highestPoints'
        ));
    }
}
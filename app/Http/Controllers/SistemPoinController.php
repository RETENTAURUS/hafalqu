<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\PoinLog;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemPoinController extends Controller
{
    // ─────────────────────────────────────────────
    // INDEX — halaman utama sistem poin
    // ─────────────────────────────────────────────
    public function index()
    {
        // Daftar quiz aktif + statistik poin
        $quizzes = Quiz::where('is_active', true)
            ->withCount('attempts')
            ->with(['attempts' => function ($q) {
                $q->whereNotNull('finished_at')->select('quiz_id', 'score', 'user_id');
            }])
            ->orderBy('order')
            ->orderBy('created_at')
            ->get()
            ->map(function ($quiz) {
                $finished       = $quiz->attempts->whereNotNull('score');
                $avgSkor        = $finished->count() > 0 ? round($finished->avg('score'), 1) : 0;
                $bobotPoin      = $quiz->bobot_poin ?? 1.00;
                $estimasiPoin   = round($avgSkor * $bobotPoin);

                return [
                    'id'             => $quiz->id,
                    'title'          => $quiz->title,
                    'bobot_poin'     => $bobotPoin,
                    'avg_skor'       => $avgSkor,
                    'estimasi_poin'  => $estimasiPoin,
                    'total_attempts' => $finished->count(),
                    'passing_score'  => $quiz->passing_score ?? 70,
                ];
            });

        // Riwayat poin terbaru (50 terakhir)
        $riwayat = PoinLog::with('user:id,name,kelas_id')
            ->latest()
            ->take(50)
            ->get();

        // Ringkasan total poin semua siswa
        $totalPoinTerbagi = PoinLog::sum('poin');
        $totalTransaksi   = PoinLog::count();
        $siswaAktif       = PoinLog::distinct('user_id')->count('user_id');

        return view('guru.poin.index', compact(
            'quizzes', 'riwayat',
            'totalPoinTerbagi', 'totalTransaksi', 'siswaAktif'
        ));
    }

    // ─────────────────────────────────────────────
    // UPDATE BOBOT — simpan bobot poin per quiz
    // ─────────────────────────────────────────────
    public function updateBobot(Request $request)
    {
        $request->validate([
            'bobots'          => 'required|array',
            'bobots.*.id'     => 'required|exists:quizzes,id',
            'bobots.*.bobot'  => 'required|numeric|min:0.1|max:10',
        ], [
            'bobots.*.bobot.min' => 'Bobot minimal 0.1',
            'bobots.*.bobot.max' => 'Bobot maksimal 10',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->bobots as $item) {
                Quiz::where('id', $item['id'])
                    ->update(['bobot_poin' => $item['bobot']]);
            }
        });

        return redirect()->route('guru.poin.index')
            ->with('success', 'Bobot poin berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────
    // REKALKUKASI — hitung ulang poin semua siswa
    // berdasarkan bobot terbaru
    // ─────────────────────────────────────────────
    public function rekalkulasi(Request $request)
    {
        $quizId = $request->quiz_id; // null = semua quiz

        $query = QuizAttempt::whereNotNull('finished_at')
            ->with(['quiz:id,bobot_poin,passing_score', 'user:id,points']);

        if ($quizId) {
            $query->where('quiz_id', $quizId);
        }

        $attempts = $query->get();

        DB::transaction(function () use ($attempts) {
            // Reset poin semua siswa terdampak ke 0 dulu
            $userIds = $attempts->pluck('user_id')->unique();
            User::whereIn('id', $userIds)->update(['points' => 0]);

            // Hapus log lama yang bersumber dari quiz
            PoinLog::whereIn('user_id', $userIds)
                   ->where('sumber', 'like', 'quiz_%')
                   ->delete();

            // Hitung ulang dari setiap attempt
            $poinPerUser = [];

            foreach ($attempts as $attempt) {
                $bobot    = $attempt->quiz->bobot_poin ?? 1.00;
                $passing  = $attempt->quiz->passing_score ?? 70;

                // Hanya attempt yang lulus yang dapat poin
                if ($attempt->score >= $passing) {
                    $poin = (int) round($attempt->score * $bobot);
                    $uid  = $attempt->user_id;

                    $poinPerUser[$uid] = ($poinPerUser[$uid] ?? 0) + $poin;

                    PoinLog::create([
                        'user_id' => $uid,
                        'poin'    => $poin,
                        'sumber'  => 'quiz_' . $attempt->quiz_id,
                    ]);
                }
            }

            // Update kolom points di tabel users
            foreach ($poinPerUser as $userId => $totalPoin) {
                User::where('id', $userId)->update(['points' => $totalPoin]);
            }
        });

        $affected = $attempts->pluck('user_id')->unique()->count();

        return redirect()->route('guru.poin.index')
            ->with('success', "Rekalkulasi selesai. {$affected} siswa telah diperbarui.");
    }
}

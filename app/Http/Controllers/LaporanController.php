<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekapKelasExport;

class LaporanController extends Controller
{
    /**
     * Ambil kelas guru yang sedang login.
     * Sesuaikan jika guru punya relasi kelas sendiri.
     */
    private function getKelasId(Request $request)
    {
        return $request->kelas_id ?? Auth::user()->kelas_id;
    }

    // ────────────────────────────────────────────────
    // INDEX — halaman utama laporan rekap kelas
    // ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $kelasId  = $this->getKelasId($request);
        $periode  = $request->periode ?? 'bulan_ini'; // bulan_ini | semester | custom
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        [$dateFrom, $dateTo] = $this->resolvePeriode($periode, $dateFrom, $dateTo);

        $data = $this->buildRekapData($kelasId, $dateFrom, $dateTo);

        // Daftar kelas untuk dropdown filter
        $kelasList = \App\Models\Kelas::orderBy('nama')->get();

        return view('guru.laporan.index', array_merge($data, compact(
            'kelasList', 'kelasId', 'periode', 'dateFrom', 'dateTo'
        )));
    }

    // ────────────────────────────────────────────────
    // EXPORT PDF
    // ────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $kelasId  = $this->getKelasId($request);
        $periode  = $request->periode ?? 'bulan_ini';
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        [$dateFrom, $dateTo] = $this->resolvePeriode($periode, $dateFrom, $dateTo);

        $data     = $this->buildRekapData($kelasId, $dateFrom, $dateTo);
        $kelas    = \App\Models\Kelas::find($kelasId);
        $namaGuru = Auth::user()->name;

        $pdf = Pdf::loadView('guru.laporan.pdf', array_merge($data, compact(
            'kelas', 'namaGuru', 'dateFrom', 'dateTo'
        )))->setPaper('a4', 'landscape');

        $filename = 'laporan-rekap-kelas-' . ($kelas->nama ?? 'semua') . '-' . now()->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    // ────────────────────────────────────────────────
    // EXPORT EXCEL
    // ────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $kelasId  = $this->getKelasId($request);
        $periode  = $request->periode ?? 'bulan_ini';
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        [$dateFrom, $dateTo] = $this->resolvePeriode($periode, $dateFrom, $dateTo);

        $data     = $this->buildRekapData($kelasId, $dateFrom, $dateTo);
        $kelas    = \App\Models\Kelas::find($kelasId);

        $filename = 'laporan-rekap-kelas-' . ($kelas->nama ?? 'semua') . '-' . now()->format('Ymd') . '.xlsx';

        return Excel::download(
            new LaporanRekapKelasExport($data, $dateFrom, $dateTo, $kelas),
            $filename
        );
    }

    // ────────────────────────────────────────────────
    // CORE — bangun semua data laporan
    // ────────────────────────────────────────────────
    private function buildRekapData($kelasId, $dateFrom, $dateTo): array
    {
        // ── Semua siswa di kelas ──────────────────────
        $siswaQuery = User::where('role', 'siswa')
            ->with(['quizAttempts' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereNotNull('finished_at')
                  ->whereBetween('finished_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                  ->with('quiz:id,title,passing_score');
            }]);

        if ($kelasId) {
            $siswaQuery->where('kelas_id', $kelasId);
        }

        $siswas = $siswaQuery->get();

        // ── 1. Tabel Ranking ─────────────────────────
        $ranking = $siswas->map(function ($user) {
            $attempts      = $user->quizAttempts;
            $selesai       = $attempts->whereNotNull('finished_at');
            $totalAttempts = $selesai->count();
            $avgScore      = $totalAttempts > 0 ? round($selesai->avg('score'), 1) : 0;
            $bestScore     = $totalAttempts > 0 ? $selesai->max('score') : 0;
            $quizDikerjakan = $selesai->pluck('quiz_id')->unique()->count();

            return [
                'id'              => $user->id,
                'name'            => $user->name,
                'points'          => $user->points ?? 0,
                'avg_score'       => $avgScore,
                'best_score'      => $bestScore,
                'total_attempts'  => $totalAttempts,
                'quiz_dikerjakan' => $quizDikerjakan,
            ];
        })->sortByDesc('points')->values()->map(function ($item, $idx) {
            $item['rank'] = $idx + 1;
            return $item;
        });

        // ── 2. Distribusi Nilai (Lulus / Tidak) ──────
        // Kumpulkan semua attempts dalam rentang, hitung per siswa per quiz nilai terbaik
        $allAttempts = $siswas->flatMap(fn($u) => $u->quizAttempts);

        $lulus      = 0;
        $tidakLulus = 0;
        $sempurna   = 0;

        // group by user_id + quiz_id, ambil skor tertinggi
        $bestPerUserQuiz = $allAttempts->groupBy(fn($a) => $a->user_id . '_' . $a->quiz_id)
            ->map(fn($group) => $group->sortByDesc('score')->first());

        foreach ($bestPerUserQuiz as $attempt) {
            $passing = $attempt->quiz->passing_score ?? 70;
            if ($attempt->score == 100)     $sempurna++;
            if ($attempt->score >= $passing) $lulus++;
            else                             $tidakLulus++;
        }

        $distribusi = [
            'sempurna'    => $sempurna,
            'lulus'       => $lulus,
            'tidak_lulus' => $tidakLulus,
            'total'       => $lulus + $tidakLulus,
        ];

        // ── 3. Siswa Aktif vs Belum ───────────────────
        $aktif  = $siswas->filter(fn($u) => $u->quizAttempts->count() > 0)->count();
        $belum  = $siswas->count() - $aktif;

        $aktivitas = [
            'aktif'        => $aktif,
            'belum'        => $belum,
            'total_siswa'  => $siswas->count(),
        ];

        // ── 4. Quiz dengan rata-rata nilai terendah ───
        $quizStats = Quiz::where('is_active', true)
            ->withCount(['attempts as total_attempts_count' => function ($q) use ($kelasId, $dateFrom, $dateTo) {
                $q->whereNotNull('finished_at')
                  ->whereBetween('finished_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
                if ($kelasId) {
                    $q->whereHas('user', fn($u) => $u->where('kelas_id', $kelasId));
                }
            }])
            ->with(['attempts' => function ($q) use ($kelasId, $dateFrom, $dateTo) {
                $q->whereNotNull('finished_at')
                  ->whereBetween('finished_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
                if ($kelasId) {
                    $q->whereHas('user', fn($u) => $u->where('kelas_id', $kelasId));
                }
            }])
            ->get()
            ->map(function ($quiz) {
                $attempts     = $quiz->attempts;
                $total        = $attempts->count();
                $avg          = $total > 0 ? round($attempts->avg('score'), 1) : null;
                $passing      = $quiz->passing_score ?? 70;
                $lulusCount   = $attempts->where('score', '>=', $passing)->count();
                $persentaseLulus = $total > 0 ? round(($lulusCount / $total) * 100) : 0;

                return [
                    'id'               => $quiz->id,
                    'title'            => $quiz->title,
                    'avg_score'        => $avg,
                    'total_attempts'   => $total,
                    'passing_score'    => $passing,
                    'persen_lulus'     => $persentaseLulus,
                ];
            })
            ->filter(fn($q) => $q['avg_score'] !== null)
            ->sortBy('avg_score')
            ->values();

        // ── 5. Summary cards ──────────────────────────
        $summary = [
            'total_siswa'     => $siswas->count(),
            'avg_class_score' => $ranking->count() > 0 ? round($ranking->avg('avg_score'), 1) : 0,
            'persen_aktif'    => $siswas->count() > 0 ? round(($aktif / $siswas->count()) * 100) : 0,
            'persen_lulus'    => $distribusi['total'] > 0
                ? round(($distribusi['lulus'] / $distribusi['total']) * 100)
                : 0,
        ];

        return compact('ranking', 'distribusi', 'aktivitas', 'quizStats', 'summary');
    }

    // ────────────────────────────────────────────────
    // HELPER — resolve periode ke date range
    // ────────────────────────────────────────────────
    private function resolvePeriode($periode, $dateFrom, $dateTo): array
    {
        return match ($periode) {
            'bulan_ini' => [now()->startOfMonth()->toDateString(), now()->toDateString()],
            'semester'  => [now()->startOfYear()->toDateString(), now()->toDateString()],
            default     => [$dateFrom, $dateTo],
        };
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use App\Models\Soal;
use App\Models\PoinLog;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\Surat;
use App\Models\Juz;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaQuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === 1. Quiz Aktif (semua tipe), DIBATASI HANYA UNTUK KELAS SISWA INI ===
        $activeQuizzes = Quiz::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) use ($user) {
                // Quiz tanpa kelas_id dianggap "untuk semua kelas".
                // Quiz dengan kelas_id hanya boleh muncul untuk siswa di kelas yang sama.
                $q->whereNull('kelas_id')
                  ->orWhere('kelas_id', $user->kelas_id); // SESUAIKAN jika struktur kelas siswa berbeda
            })
            ->with('soals')
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        // === 2. Pecah jadi 2 jalur independen berdasarkan tipe_pengerjaan ===
        $sekolahQuizzes = $activeQuizzes->where('tipe_pengerjaan', 'sekolah')->values();
        $rumahQuizzes   = $activeQuizzes->where('tipe_pengerjaan', 'rumah')->values();

        // === 3. Quiz Sudah Dikerjakan (riwayat, lintas jalur) ===
        $attemptedQuizzes = QuizAttempt::with(['quiz.soals'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($attemptedQuizzes as $attempt) {
            $attempt->total_questions = $attempt->soals()->count() > 0 ? $attempt->soals()->count() : ($attempt->quiz ? $attempt->quiz->soals->count() : 0);
            $attempt->correct_answers = AttemptAnswer::where('quiz_attempt_id', $attempt->id)
                ->where('is_correct', true)
                ->count();
        }

        // === 4. Status Quiz, dihitung terpisah per jalur ===
        $allAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereIn('quiz_id', $activeQuizzes->pluck('id'))
            ->get()
            ->groupBy('quiz_id');

        $sekolahStatus = $this->buildQuizStatus($sekolahQuizzes, $allAttempts);
        $rumahStatus   = $this->buildQuizStatus($rumahQuizzes, $allAttempts);

        return view('siswa.quiz.index', compact(
            'sekolahQuizzes', 'rumahQuizzes',
            'sekolahStatus', 'rumahStatus',
            'attemptedQuizzes'
        ));
    }

    /**
     * Hitung status untuk satu jalur quiz
     */
    private function buildQuizStatus($quizzes, $allAttempts): array
    {
        $status          = [];
        $previousPerfect = true;

        foreach ($quizzes as $quiz) {
            $attempts     = $allAttempts->get($quiz->id, collect());
            $attemptCount = $attempts->count();
            $isPerfect    = $attempts->where('score', 100)->isNotEmpty();
            $passingScore = $quiz->passing_score ?? 70;
            $isCompleted  = $attempts->where('score', '>=', $passingScore)->isNotEmpty();

            $maxAttempts = ($quiz->attempt_limit > 0) ? $quiz->attempt_limit : null;
            $remaining   = $maxAttempts === null
                ? PHP_INT_MAX
                : max(0, $maxAttempts - $attemptCount);

            $canAccess = false;
            $message   = '';

            if (!$previousPerfect) {
                $canAccess = false;
                $message   = 'Selesaikan quiz sebelumnya dengan nilai sempurna.';
            } elseif ($remaining <= 0) {
                $canAccess = false;
                $message   = 'Batas percobaan habis.';
            } else {
                $canAccess = true;
                $message   = $isPerfect ? 'Ulangi' : ($attemptCount > 0 ? 'Coba Lagi' : 'Mulai');
            }

            $previousPerfect = $isPerfect;

            $status[$quiz->id] = [
                'canAccess'   => $canAccess,
                'message'     => $message,
                'isPerfect'   => $isPerfect,
                'isCompleted' => $isCompleted,
                'attempts'    => $attemptCount,
                'remaining'   => $remaining,
            ];
        }

        return $status;
    }

    public function confirm($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with('soals')->findOrFail($quizId);

        if (!$this->canAccessQuiz($user->id, $quiz)) {
            return redirect()->route('siswa.quiz.index')->with('error', 'Quiz ini tidak dapat diakses.');
        }

        $totalSoal = $quiz->soals->count();

        // Poin maksimal = skor sempurna (100) dikali bobot poin quiz ini,
        // konsisten dengan rumus perhitungan poin di finish().
        $bobot        = $quiz->bobot_poin ?? 1.00;
        $poinMaksimal = (int) round(100 * $bobot);

        $maxAttempts  = ($quiz->attempt_limit > 0) ? $quiz->attempt_limit : null;
        $attemptCount = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();
        $sisaPercobaan = $maxAttempts === null ? null : max(0, $maxAttempts - $attemptCount);

        return view('siswa.quiz.confirm', compact(
            'quiz', 'totalSoal', 'poinMaksimal', 'maxAttempts', 'attemptCount', 'sisaPercobaan'
        ));
    }

    /**
     * Membuat Sesi Percobaan Baru dengan Soal yang Diacak dari Aturan Kuis
     */
   public function start($quizId)
{
    $user = Auth::user();
    $quiz = Quiz::findOrFail($quizId);

    if (!$this->canAccessQuiz($user->id, $quiz)) {
        return redirect()->route('siswa.quiz.index')->with('error', 'Quiz ini tidak dapat diakses.');
    }

    $unfinished = QuizAttempt::where('user_id', $user->id)
        ->where('quiz_id', $quiz->id)
        ->whereNull('finished_at')
        ->first();

    if ($unfinished) {
        return redirect()->route('siswa.quiz.do', $unfinished->id)
            ->with('info', 'Lanjutkan quiz yang belum selesai.');
    }

    // Soal yang SUDAH PERNAH diberikan ke siswa ini pada quiz ini di attempt sebelumnya
    $usedSoalIds = \DB::table('attempt_soal')
        ->join('quiz_attempts', 'quiz_attempts.id', '=', 'attempt_soal.quiz_attempt_id')
        ->where('quiz_attempts.user_id', $user->id)
        ->where('quiz_attempts.quiz_id', $quiz->id)
        ->pluck('attempt_soal.soal_id')
        ->toArray();

    $attempt = QuizAttempt::create([
        'user_id'         => $user->id,
        'quiz_id'         => $quiz->id,
        'score'           => 0,
        'total_questions' => 0,
        'correct_answers' => 0,
        'started_at'      => now(),
    ]);

    $quizConfig = json_decode($quiz->config, true) ?? [];
    $generateSoalIds = [];

    if (!empty($quizConfig)) {
        foreach ($quizConfig as $suratId => $aturan) {
            $baseQuery = fn () => Soal::where('surat_id', $suratId)
                ->when(!empty($aturan['jenis']), fn ($q) => $q->whereIn('jenis', $aturan['jenis']))
                ->when(($aturan['kesulitan'] ?? 'semua') !== 'semua', fn ($q) =>
                    $q->where('kesulitan', ucfirst(strtolower($aturan['kesulitan']))));

            $jumlahSoal = $aturan['jumlah'] ?? 5;

            // 1. Prioritaskan soal yang BELUM PERNAH keluar untuk siswa ini
            $freshIds = (clone $baseQuery())
                ->whereNotIn('id', $usedSoalIds)
                ->inRandomOrder()
                ->limit($jumlahSoal)
                ->pluck('id')
                ->toArray();

            // 2. Kalau soal segar tidak cukup (bank soal sudah "habis" dieksplorasi),
            //    baru ambil dari soal lama sebagai pelengkap
            if (count($freshIds) < $jumlahSoal) {
                $kurang = $jumlahSoal - count($freshIds);
                $fallbackIds = (clone $baseQuery())
                    ->whereNotIn('id', $freshIds)
                    ->inRandomOrder()
                    ->limit($kurang)
                    ->pluck('id')
                    ->toArray();
                $freshIds = array_merge($freshIds, $fallbackIds);
            }

            $generateSoalIds = array_merge($generateSoalIds, $freshIds);
        }
    }

    if (empty($generateSoalIds)) {
        $generateSoalIds = $quiz->soals()->inRandomOrder()->pluck('soals.id')->toArray();
    }

    $order = 1;
    $attachData = [];
    foreach ($generateSoalIds as $soalId) {
        $attachData[$soalId] = ['order' => $order++];
    }
    $attempt->soals()->attach($attachData); // sync sekali, bukan query berulang di loop

    $attempt->update(['total_questions' => count($generateSoalIds)]);

    return redirect()->route('siswa.quiz.do', $attempt->id);
}

    /**
     * Menampilkan Soal dari Pivot Percobaan (attempt_soal)
     */
    public function doQuiz($attemptId)
    {
        $attempt = QuizAttempt::with(['quiz', 'answers'])->findOrFail($attemptId);

        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->finished_at) {
            return redirect()->route('siswa.quiz.result', $attempt->id);
        }

        $quiz = $attempt->quiz;

        // Pengaman tambahan: jika quiz sudah dipindah ke kelas lain setelah attempt
        // dibuat, jangan biarkan siswa lanjut mengerjakan.
        if (!$this->canAccessQuiz($attempt->user_id, $quiz, ignoreAttemptLimit: true)) {
            abort(403);
        }

        // PERBAIKAN UTAMA: Mengambil soal acak dari sesi attempt ini, bukan kuis statis
        $soals = $attempt->soals()->orderBy('attempt_soal.order')->get();
        $answeredSoalIds = $attempt->answers()->pluck('soal_id')->toArray();

        $started          = $attempt->started_at->copy();
        $duration         = $quiz->duration ?? 30;
        $timeLimit        = $started->addMinutes($duration);
        $remainingSeconds = (int) max(0, now()->diffInSeconds($timeLimit, false));

        if ($remainingSeconds <= 0) {
            return $this->finish($attemptId);
        }

        return view('siswa.quiz.do', compact('attempt', 'quiz', 'soals', 'answeredSoalIds', 'remainingSeconds'));
    }

    public function saveAnswer(Request $request, $attemptId)
    {
        $attempt = QuizAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $soal      = Soal::find($request->soal_id);
        $isCorrect = $soal && $soal->jawaban_benar === $request->answer;

        AttemptAnswer::updateOrCreate(
            ['quiz_attempt_id' => $attemptId, 'soal_id' => $request->soal_id],
            ['selected_answer' => $request->answer, 'is_correct' => $isCorrect]
        );

        // Hitung total jawaban benar terkini
        $correctCount = $attempt->answers()->where('is_correct', true)->count();
        $attempt->update(['correct_answers' => $correctCount]);

        $answered = $attempt->answers()->count();
        return response()->json(['success' => true, 'answered' => $answered]);
    }

    public function riwayat()
    {
        $attemptedQuizzes = QuizAttempt::with('quiz')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('siswa.riwayat', compact('attemptedQuizzes'));
    }

    public function getAnswers($attemptId)
    {
        $attempt = QuizAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $soalIds = $attempt->answers()->pluck('soal_id')->toArray();
        return response()->json(['answered' => count($soalIds), 'soal_ids' => $soalIds]);
    }

public function finish($attemptId)
{
    $attempt = QuizAttempt::findOrFail($attemptId);
    if ($attempt->user_id !== Auth::id()) abort(403);

    if ($attempt->finished_at) {
        return redirect()->route('siswa.quiz.result', $attempt->id);
    }

    $totalQuestions = $attempt->soals()->count();
    if ($totalQuestions === 0) {
        $totalQuestions = $attempt->total_questions > 0 ? $attempt->total_questions : 1;
    }

    $answers = $attempt->answers;
    $correctCount = $answers->where('is_correct', true)->count();

    $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

    $attempt->update([
        'score'           => $score,
        'correct_answers' => $correctCount,
        'finished_at'     => now()
    ]);

    $quiz = $attempt->quiz;
    $user = $attempt->user;

    // Simpan poin awal SEKALI di sini, sebelum penambahan apapun (poin dasar
    // maupun bonus kecepatan), supaya pengecekan naik level di akhir akurat
    // walau kedua sumber poin sama-sama cair pada attempt yang sama.
    $pointsBefore = $user->points ?? 0;

    if ($score >= ($quiz->passing_score ?? 70)) {
        $bobot = $quiz->bobot_poin ?? 1.00;
        $poin  = (int) round($score * $bobot);

        // Cari skor TERTINGGI sebelumnya untuk quiz ini (selain attempt sekarang)
        $previousBestScore = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('id', '!=', $attempt->id)
            ->whereNotNull('finished_at')
            ->max('score') ?? 0;

        $previousBestPoin = (int) round($previousBestScore * $bobot);

        // Hanya beri poin kalau skor sekarang MENGALAHKAN rekor sebelumnya
        if ($poin > $previousBestPoin) {
            $selisihPoin = $poin - $previousBestPoin;

            $user->increment('points', $selisihPoin);
            $user->refresh();

            \App\Models\PoinLog::create([
                'user_id' => $user->id,
                'poin'    => $selisihPoin,
                'sumber'  => 'quiz_' . $quiz->id,
            ]);
        }
    }

    // === Bonus Kecepatan: skor sempurna (100) pada PERCOBAAN PERTAMA ===
    // Bonus = persentase sisa waktu saat menyelesaikan, langsung dijadikan poin.
    // Contoh: durasi 30 menit, selesai dengan sisa 24 menit (80% waktu tersisa)
    // -> bonus +80 poin.
    if ($score == 100) {
        $firstAttemptId = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('id')
            ->value('id');

        $isFirstAttempt = $firstAttemptId === $attempt->id;

        if ($isFirstAttempt) {
            $durationSeconds = ($quiz->duration ?? 30) * 60;
            $elapsedSeconds  = max(0, $attempt->started_at->diffInSeconds($attempt->finished_at));
            $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

            $persenSisaWaktu = $durationSeconds > 0
                ? ($remainingSeconds / $durationSeconds) * 100
                : 0;

            $bonusPoin = (int) round($persenSisaWaktu);

            if ($bonusPoin > 0) {
                $user->increment('points', $bonusPoin);
                $user->refresh();

                \App\Models\PoinLog::create([
                    'user_id' => $user->id,
                    'poin'    => $bonusPoin,
                    'sumber'  => 'bonus_kecepatan_quiz_' . $quiz->id,
                ]);

                session()->flash('bonus_kecepatan', [
                    'poin'  => $bonusPoin,
                    'sisa'  => round($persenSisaWaktu),
                ]);
            }
        }
    }

    // Cek naik level SEKALI di akhir, membandingkan poin sebelum vs sesudah
    // semua penambahan (poin dasar + bonus kecepatan) di attempt ini.
    if ($user->points > $pointsBefore) {
        $levelNaik = \App\Models\Level::checkLevelUp($pointsBefore, $user->points);
        if ($levelNaik) {
            session()->flash('level_up', [
                'nama' => $levelNaik->name,
                'poin' => $user->points,
            ]);

            \App\Models\PoinLog::create([
                'user_id' => $user->id,
                'poin'    => 0,
                'sumber'  => 'naik_level_' . $levelNaik->id,
            ]);
        }
    }

    $this->checkAndAwardBadges($user->id);

    return redirect()->route('siswa.quiz.result', $attempt->id);
}

    private function checkAndAwardBadges($userId)
    {
        $user = User::find($userId);
        $allBadges = Badge::where('is_active', true)->get();

        foreach ($allBadges as $badge) {
            $alreadyOwned = UserBadge::where('user_id', $userId)
                ->where('badge_id', $badge->id)
                ->exists();

            if ($alreadyOwned) continue;

            $met = $this->isBadgeCriteriaMet($user, $badge);

            if ($met) {
                UserBadge::create([
                    'user_id' => $userId,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);

                if ($badge->required_points > 0) {
                    $user->increment('points', $badge->required_points);
                    PoinLog::create([
                        'user_id' => $userId,
                        'sumber' => 'Lencana: ' . $badge->name,
                        'poin' => $badge->required_points,
                    ]);
                }
            }
        }
    }

    private function isBadgeCriteriaMet($user, $badge)
    {
        switch ($badge->criteria_type) {
            case 'poin':
                return ($user->points ?? 0) >= $badge->criteria_value;

            case 'quiz_selesai':
                $count = $user->quizAttempts()
                    ->whereNotNull('finished_at')
                    ->where('score', '>=', 70)
                    ->count();
                return $count >= $badge->criteria_value;

            case 'nilai_sempurna':
                $count = $user->quizAttempts()
                    ->whereNotNull('finished_at')
                    ->where('score', 100)
                    ->count();
                return $count >= $badge->criteria_value;

            case 'hafalan':
                $count = $user->hafalans()->count();
                return $count >= $badge->criteria_value;

            case 'juz_selesai':
                if ($badge->juz_id) {
                    $quizIds = \App\Models\Quiz::where('juz_id', $badge->juz_id)
                        ->where('is_active', true)
                        ->pluck('id');

                    if ($quizIds->isEmpty()) {
                        return false;
                    }

                    $completedCount = $user->quizAttempts()
                        ->whereIn('quiz_id', $quizIds)
                        ->whereNotNull('finished_at')
                        ->where('score', 100)
                        ->distinct('quiz_id')
                        ->count('quiz_id');

                    return $completedCount >= $quizIds->count();
                }

                $completedJuzCount = UserBadge::where('user_id', $user->id)
                    ->whereIn('badge_id', Badge::where('criteria_type', 'juz_selesai')
                        ->whereNotNull('juz_id')
                        ->pluck('id'))
                    ->count();
                return $completedJuzCount >= $badge->criteria_value;

            case 'leaderboard_rank':
                $rank = User::where('points', '>', $user->points ?? 0)->count() + 1;
                return $rank <= $badge->criteria_value;

            case 'login_streak':
                return ($user->login_streak ?? 0) >= $badge->criteria_value;

            default:
                return false;
        }
    }

    public function result($attemptId)
    {
        $attempt = QuizAttempt::with(['quiz', 'user', 'answers'])->findOrFail($attemptId);
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $quiz         = $attempt->quiz;
        $totalSoal    = $attempt->soals()->count();
        if ($totalSoal === 0) {
            $totalSoal = $attempt->total_questions > 0 ? $attempt->total_questions : $quiz->soals()->count();
        }

        $answered     = $attempt->answers()->count();
        $score        = $attempt->score;
        $isPerfect    = ($score == 100);
        $isPassed     = ($score >= ($quiz->passing_score ?? 70));
        $correctCount = $attempt->answers()->where('is_correct', true)->count();
        $wrongCount   = $attempt->answers()->where('is_correct', false)->count();
        $notAnswered  = $totalSoal - $answered;

        $maxAttempts  = ($quiz->attempt_limit > 0) ? $quiz->attempt_limit : null;
        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();
        $canRetry = $maxAttempts === null || $attemptCount < $maxAttempts;

        return view('siswa.quiz.result', compact(
            'attempt', 'quiz', 'totalSoal', 'answered',
            'score', 'isPerfect', 'isPassed',
            'correctCount', 'wrongCount', 'notAnswered', 'canRetry'
        ));
    }

    public function continueQuiz($attemptId)
    {
        $attempt = QuizAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->finished_at) {
            return redirect()->route('siswa.quiz.result', $attempt->id);
        }

        return redirect()->route('siswa.quiz.do', $attempt->id);
    }

    /**
     * Otorisasi akses quiz untuk siswa:
     *  - Quiz harus untuk kelas siswa ini (atau berlaku untuk semua kelas)
     *  - Quiz sebelumnya (berdasarkan urutan & tipe pengerjaan) harus sudah
     *    dituntaskan dengan nilai sempurna
     *  - Batas percobaan belum habis
     *
     * @param  int   $userId
     * @param  Quiz  $quiz
     * @param  bool  $ignoreAttemptLimit  set true saat memvalidasi attempt yang
     *                                    sedang berjalan (mis. di doQuiz()), supaya
     *                                    siswa yang sudah mulai tidak diblokir hanya
     *                                    karena attempt_limit, tapi tetap diblokir
     *                                    kalau memang bukan dari kelas yang benar.
     */
    private function canAccessQuiz($userId, $quiz, bool $ignoreAttemptLimit = false)
    {
        $user = User::find($userId);

        // === Cek kecocokan kelas ===
        // Quiz tanpa kelas_id = berlaku untuk semua kelas.
        // Quiz dengan kelas_id hanya boleh diakses siswa dari kelas yang sama.
        if ($quiz->kelas_id && (!$user || $quiz->kelas_id !== $user->kelas_id)) { // SESUAIKAN jika struktur kelas siswa berbeda
            return false;
        }

        $previousQuiz = Quiz::where('tipe_pengerjaan', $quiz->tipe_pengerjaan)
            ->where('order', '<', $quiz->order)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousQuiz) {
            $prevPerfect = QuizAttempt::where('user_id', $userId)
                ->where('quiz_id', $previousQuiz->id)
                ->where('score', 100)
                ->exists();

            if (!$prevPerfect) {
                return false;
            }
        }

        if ($ignoreAttemptLimit) {
            return true;
        }

        $maxAttempts  = ($quiz->attempt_limit > 0) ? $quiz->attempt_limit : null;
        $attemptCount = QuizAttempt::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($maxAttempts !== null && $attemptCount >= $maxAttempts) {
            return false;
        }

        return true;
    }
}
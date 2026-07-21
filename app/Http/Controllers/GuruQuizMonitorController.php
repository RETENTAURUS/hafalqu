<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;
use Illuminate\Http\Request;

class GuruQuizMonitorController extends Controller
{
    /**
     * Halaman pemantauan langsung. Hanya untuk quiz tipe "sekolah" karena
     * quiz "rumah" dikerjakan mandiri di luar jam sekolah — memantaunya
     * secara live tidak relevan untuk kasus itu.
     */
    public function live($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        if ($quiz->tipe_pengerjaan !== 'sekolah') {
            abort(403, 'Pemantauan langsung hanya tersedia untuk quiz dengan tipe pengerjaan "Di Sekolah".');
        }

        return view('guru.quiz.live', compact('quiz'));
    }

    /**
     * Endpoint JSON yang di-poll oleh halaman live setiap beberapa detik.
     */
    public function liveData($quizId)
    {
        $quiz      = Quiz::findOrFail($quizId);
        $totalSoal = $quiz->soals()->count();

        $students = QuizAttempt::with('user')
            ->where('quiz_id', $quizId)
            ->whereDate('created_at', today())
            ->orderByDesc('started_at')
            ->get()
            ->map(function ($attempt) use ($totalSoal) {
                $answered = AttemptAnswer::where('quiz_attempt_id', $attempt->id)->count();

                return [
                    'attempt_id'  => $attempt->id,
                    'nama'        => $attempt->user->name ?? 'Siswa',
                    'answered'    => $answered,
                    'total_soal'  => $totalSoal,
                    'percent'     => $totalSoal > 0 ? (int) round(($answered / $totalSoal) * 100) : 0,
                    'status'      => $attempt->finished_at ? 'selesai' : 'mengerjakan',
                    'score'       => $attempt->finished_at ? $attempt->score : null,
                    'started_at'  => optional($attempt->started_at)->format('H:i'),
                    'finished_at' => optional($attempt->finished_at)->format('H:i'),
                ];
            });

        return response()->json([
            'quiz_title' => $quiz->title,
            'total_soal' => $totalSoal,
            'students'   => $students,
            'summary'    => [
                'total_mengikuti'    => $students->count(),
                'sedang_mengerjakan' => $students->where('status', 'mengerjakan')->count(),
                'sudah_selesai'      => $students->where('status', 'selesai')->count(),
            ],
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
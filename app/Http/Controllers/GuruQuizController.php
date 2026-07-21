<?php

namespace App\Http\Controllers;

use App\Models\Juz;
use App\Models\Surat;
use App\Models\Soal;
use App\Models\Quiz;
use Illuminate\Http\Request;

class GuruQuizController extends Controller
{
    /**
     * Tampilan Utama: Menggabungkan Daftar Kotak Juz & Tabel Quiz Lengkap
     */
    public function index()
    {
        $daftarJuz = Juz::withCount('surats')->orderBy('nomor')->get();
        $quizzes = Quiz::with(['juz', 'kelas'])->withCount('soals')->orderBy('created_at', 'desc')->get();

        return view('guru.quiz.index', compact('daftarJuz', 'quizzes'));
    }

    /**
     * Sinkronisasi langkah 1: Diarahkan ke index karena halaman sudah digabung
     */
    public function pilihJuz()
    {
        return redirect()->route('guru.quiz.index');
    }

    /**
     * Halaman daftar Surat dalam Juz (langkah 2)
     */
    public function pilihSurat(Request $request, $juz_id)
    {
        $juz = Juz::with(['surats' => function($query) {
            $query->withCount('soals')->orderBy('nomor_surat');
        }])->findOrFail($juz_id);

        if ($request->has('surat_ids')) {
            $selected = $request->input('surat_ids', []);
            if (is_array($selected) && count($selected) > 0) {
                session([
                    'selected_surat_ids' => $selected,
                    'selected_juz_id'    => $juz_id
                ]);
                return redirect()->route('guru.quiz.konfigurasi');
            }
            return back()->with('error', 'Pilih minimal satu surat.');
        }

        return view('guru.quiz.pilih-surat', compact('juz'));
    }

    /**
     * Halaman konfigurasi quiz (langkah 3)
     */
    public function konfigurasi(Request $request)
    {
        $suratIds = session('selected_surat_ids', []);
        
        if (empty($suratIds)) {
            return redirect()->route('guru.quiz.index')
                ->with('error', 'Silakan pilih surat terlebih dahulu.');
        }

        $surats = Surat::withCount('soals')->whereIn('id', $suratIds)->get();

        if (!session()->has('quiz_config')) {
            $config = [];
            foreach ($surats as $surat) {
                $config[$surat->id] = [
                    'jumlah'    => min(10, $surat->soals_count),
                    'jenis'     => ['melanjutkan', 'mengisi', 'pengetahuan', 'audio'],
                    'kesulitan' => 'semua',
                ];
            }
            session(['quiz_config' => $config]);
        }

        $config = session('quiz_config');

        return view('guru.quiz.konfigurasi', compact('surats', 'config'));
    }

    /**
     * Simpan konfigurasi quiz
     */
    public function simpanKonfigurasi(Request $request)
    {
        $request->validate([
            'konfigurasi' => 'required|array',
            'konfigurasi.*.jumlah'    => 'required|integer|min:1',
            'konfigurasi.*.jenis'     => 'nullable|array',
            'konfigurasi.*.kesulitan' => 'required|in:semua,mudah,sedang,sulit',
        ]);

        session(['quiz_config' => $request->konfigurasi]);

        return redirect()->route('guru.quiz.preview')
            ->with('success', 'Konfigurasi berhasil disimpan.');
    }

    /**
     * Preview Quiz (langkah 4)
     */
    public function preview()
    {
        $suratIds = session('selected_surat_ids', []);
        $config = session('quiz_config', []);
        $juzId = session('selected_juz_id');

        if (empty($suratIds) || empty($config)) {
            return redirect()->route('guru.quiz.index')
                ->with('error', 'Silakan pilih surat dan konfigurasi quiz terlebih dahulu.');
        }

        $surats = Surat::with('soals')->whereIn('id', $suratIds)->get();
        $juz = $juzId ? Juz::find($juzId) : null; 

        $totalSoal = 0;
        foreach ($surats as $surat) {
            $totalSoal += $config[$surat->id]['jumlah'] ?? 0;
        }

        return view('guru.quiz.preview', compact('surats', 'config', 'totalSoal', 'juz'));
    }

    /**
     * Statistik & Nilai Kuis Siswa
     */
    public function lihatNilai($id)
    {
        $quiz = Quiz::findOrFail($id);
        $attempts = $quiz->attempts()->with('siswa')->get(); 

        $students = $attempts->map(function($att) use ($quiz) {
            $totalSoal = $quiz->soals_count ?? 10; 
            return (object) [
                'nama'       => $att->siswa->nama,
                'answered'   => $att->jumlah_terjawab,
                'total_soal' => $totalSoal,
                'percent'    => ($att->jumlah_terjawab / $totalSoal) * 100,
                'status'     => $att->is_finished ? 'selesai' : 'mengerjakan',
                'score'      => $att->skor,
                'started_at' => $att->created_at->format('H:i'),
            ];
        });

        $summary = [
            'total_mengikuti'    => $students->count(),
            'sedang_mengerjakan' => $students->where('status', 'mengerjakan')->count(),
            'sudah_selesai'      => $students->where('status', 'selesai')->count(),
        ];

        return view('guru.quiz.nilai', compact('quiz', 'students', 'summary'));
    }

    /**
     * Menyimpan Aturan Blueprint Konfigurasi & Mengunci Set Soal Awal
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'kelas_id'        => 'nullable|exists:kelas,id',
            'juz_id'          => 'nullable|exists:juz,id',
            'tipe_pengerjaan' => 'required|in:sekolah,rumah',
            'duration'        => 'nullable|integer|min:1',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after:start_date',
            'attempt_limit'   => 'nullable|integer|min:0',
        ]);

        $suratIds = session('selected_surat_ids', []);
        $config   = session('quiz_config', []);

        if (empty($suratIds) || empty($config)) {
            return redirect()->route('guru.quiz.index')->with('error', 'Konfigurasi tidak ditemukan.');
        }

        $lastOrder = Quiz::max('order') ?? 0;
        $startDate = $request->filled('start_date') ? $request->start_date : null;
        $endDate   = $request->filled('end_date')   ? $request->end_date   : null;

        $quiz = Quiz::create([
            'title'           => $request->title,
            'kelas_id'        => $request->kelas_id,
            'juz_id'          => $request->juz_id,
            'tipe_pengerjaan' => $request->tipe_pengerjaan,
            'is_active'       => true,
            'duration'        => $request->duration ?? 30,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'attempt_limit'   => $request->attempt_limit ?? 0,
            'order'           => $lastOrder + 1,
            'config'          => json_encode($config), // Blueprint Aturan Utama
        ]);

        $soalIds = [];
        foreach ($suratIds as $suratId) {
            $query = Soal::where('surat_id', $suratId);

            if (!empty($config[$suratId]['jenis'])) {
                $query->whereIn('jenis', $config[$suratId]['jenis']);
            }

            if (isset($config[$suratId]['kesulitan']) && $config[$suratId]['kesulitan'] !== 'semua') {
                $tingkatKesulitan = ucfirst(strtolower($config[$suratId]['kesulitan'])); 
                $query->where('kesulitan', $tingkatKesulitan);
            }

            $jumlah = $config[$suratId]['jumlah'] ?? 5;
            $soals  = $query->inRandomOrder()->limit($jumlah)->get();

            foreach ($soals as $soal) {
                $soalIds[] = $soal->id;
            }
        }

        $order = 1;
        foreach ($soalIds as $soalId) {
            $quiz->soals()->attach($soalId, ['order' => $order++]);
        }

        session()->forget(['selected_surat_ids', 'quiz_config']);

        return redirect()->route('guru.quiz.index')
            ->with('success', 'Quiz "' . $quiz->title . '" berhasil dibuat!');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->soals()->detach();
        $quiz->delete();
        return redirect()->route('guru.quiz.index')->with('success', 'Quiz berhasil dihapus.');
    }
}
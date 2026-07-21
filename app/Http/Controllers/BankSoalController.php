<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Juz;
use App\Models\Surat;
use App\Models\Soal;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    /**
     * Menampilkan Halaman Utama Bank Soal
     */
    public function index()
    {
        $daftarJuz = Juz::orderBy('nomor', 'asc')->get();

        $totalJuz   = Juz::count();
        $totalSurat = Surat::count();
        $totalSoal  = Soal::count();
        $soalAudio  = Soal::where('jenis', 'audio')->count();

        return view('guru.bank-soal', compact(
            'daftarJuz',
            'totalJuz',
            'totalSurat',
            'totalSoal',
            'soalAudio'
        ));
    }

    /**
     * Menyimpan Data Juz Baru
     */
    public function storeJuz(Request $request)
    {
        $request->validate([
            'nomor_juz' => 'required|integer|between:1,30|unique:juz,nomor',
        ], [
            'nomor_juz.required' => 'Nomor juz wajib diisi.',
            'nomor_juz.unique'   => 'Juz ini sudah terdaftar di bank soal.',
            'nomor_juz.between'  => 'Nomor juz harus berada di antara rentang 1 sampai 30.'
        ]);

        Juz::create(['nomor' => $request->nomor_juz]);

        return redirect()->route('guru.soal.index')->with('success', 'Juz baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan Daftar Surat dalam Juz tertentu
     */
    public function showJuz(Request $request, $juz_id)
    {
        $juz = Juz::with(['surats' => function($query) {
            $query->orderBy('nomor_surat', 'asc');
        }])->findOrFail($juz_id);

        $suratIdAktif = $request->query('surat_id') ?? ($juz->surats->first()->id ?? null);
        $suratAktif = null;
        if ($suratIdAktif) {
            $suratAktif = Surat::with('soals')->find($suratIdAktif);
        }

        return view('guru.bank-soal-surat', compact('juz', 'suratAktif'));
    }

    /**
     * Menyimpan Surah Baru
     */
    public function storeSurat(Request $request, $juz_id)
    {
        $request->validate([
            'nomor_surat' => 'required|integer|min:1',
            'nama_surat'  => 'required|string|max:100',
            'total_ayat'  => 'required|integer|min:1',
        ]);

        Surat::create([
            'juz_id'      => $juz_id,
            'nomor_surat' => $request->nomor_surat,
            'nama_surat'  => $request->nama_surat,
            'total_ayat'  => $request->total_ayat,
        ]);

        return redirect()->route('guru.soal.showJuz', $juz_id)->with('success', 'Surat baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan Daftar Soal di dalam Surat
     */
    public function showSurat($surat_id)
    {
        $surat = Surat::with('soals')->findOrFail($surat_id);
        return view('guru.bank-soal-list-soal', compact('surat'));
    }

    /**
     * MENYIMPAN SOAL BARU (DENGAN PILIHAN GANDA)
     */
    public function storeSoal(Request $request)
    {
        $request->validate([
            'surat_id'      => 'required|exists:surats,id',
            'pertanyaan'    => 'required|string',
            'kesulitan'     => 'required|in:Mudah,Sedang,Sulit', // <-- PERBAIKAN: Huruf kapital di awal
            'jenis'         => 'required|in:melanjutkan,mengisi,pengetahuan,audio',
            'file_audio'    => 'nullable|required_if:jenis,audio|mimes:mp3,wav,ogg|max:5000',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'poin'          => 'required|integer|min:1', // <-- Tambahkan validasi poin biar aman
        ]);

        $data = $request->only([
            'surat_id',
            'pertanyaan',
            'kesulitan', // <-- PERBAIKAN: Harus di-only agar masuk ke query insert
            'jenis',
            'opsi_a',
            'opsi_b',
            'opsi_c',
            'opsi_d',
            'jawaban_benar',
            'poin' // <-- PERBAIKAN: Harus di-only agar masuk ke query insert
        ]);

        // Proses upload file audio jika jenis = audio
        if ($request->jenis === 'audio' && $request->hasFile('file_audio')) {
            $file = $request->file('file_audio');
            $fileName = 'audio_soal_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/audio_soals', $fileName);
            $data['file_audio'] = $fileName;
        }

        Soal::create($data);

        return back()->with('success', 'Soal baru berhasil ditambahkan ke bank soal!');
    }

    public function showSoal($id)
    {
        $soal = \App\Models\Soal::with('surat.juz')->findOrFail($id);
        return response()->json([
            'id'            => $soal->id,
            'pertanyaan'    => $soal->pertanyaan,
            'jenis'         => $soal->jenis,
            'opsi_a'        => $soal->opsi_a,
            'opsi_b'        => $soal->opsi_b,
            'opsi_c'        => $soal->opsi_c,
            'opsi_d'        => $soal->opsi_d,
            'jawaban_benar' => $soal->jawaban_benar,
            'kesulitan'     => $soal->kesulitan ?? 'Mudah',
            'poin'          => $soal->poin ?? 100,
            'file_audio'    => $soal->file_audio ? asset('storage/audio_soals/' . $soal->file_audio) : null,
            'surat'         => $soal->surat?->nama_surat,
        ]);
    }
     
    public function editSoal($id)
    {
        $soal = \App\Models\Soal::with('surat.juz')->findOrFail($id);
        return response()->json([
            'id'            => $soal->id,
            'pertanyaan'    => $soal->pertanyaan,
            'jenis'         => $soal->jenis,
            'opsi_a'        => $soal->opsi_a,
            'opsi_b'        => $soal->opsi_b,
            'opsi_c'        => $soal->opsi_c,
            'opsi_d'        => $soal->opsi_d,
            'jawaban_benar' => $soal->jawaban_benar,
            'kesulitan'     => $soal->kesulitan ?? 'Mudah',
            'poin'          => $soal->poin ?? 100,
            'file_audio'    => $soal->file_audio,
            'surat_id'      => $soal->surat_id,
        ]);
    }
     
    public function update(Request $request, $id)
    {
        $soal = \App\Models\Soal::findOrFail($id);
     
        $request->validate([
            'pertanyaan'    => 'required|string',
            'jenis'         => 'required|in:melanjutkan,mengisi,pengetahuan,audio',
            'opsi_a'        => 'required|string|max:255',
            'opsi_b'        => 'required|string|max:255',
            'opsi_c'        => 'required|string|max:255',
            'opsi_d'        => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'kesulitan'     => 'required|in:Mudah,Sedang,Sulit', // <-- PERBAIKAN: Huruf kapital di awal
            'poin'          => 'required|integer|min:1',
            'file_audio'    => 'nullable|file|mimes:mp3,wav,ogg|max:5120',
        ]);
     
        $data = $request->only([
            'pertanyaan','jenis','opsi_a','opsi_b','opsi_c','opsi_d',
            'jawaban_benar','kesulitan','poin',
        ]);
     
        if ($request->hasFile('file_audio')) {
            if ($soal->file_audio) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($soal->file_audio);
            }
            $data['file_audio'] = $request->file('file_audio')->store('audio_soal', 'public');
        }
     
        $soal->update($data);
     
        return redirect()->back()->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * MEMPERBARUI SOAL (DENGAN PILIHAN GANDA)
     */
    public function updateSoal(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);

        $request->validate([
            'pertanyaan'    => 'required|string',
            'kesulitan'     => 'required|in:Mudah,Sedang,Sulit', // <-- PERBAIKAN: Huruf kapital di awal
            'jenis'         => 'required|in:melanjutkan,mengisi,pengetahuan,audio',
            'file_audio'    => 'nullable|mimes:mp3,wav,ogg|max:5000',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'poin'          => 'required|integer|min:1', // <-- Tambahkan validasi poin biar aman
        ]);

        $data = $request->only([
            'pertanyaan',
            'kesulitan', // <-- PERBAIKAN: Harus di-only agar masuk ke query update
            'jenis',
            'opsi_a',
            'opsi_b',
            'opsi_c',
            'opsi_d',
            'jawaban_benar',
            'poin' // <-- PERBAIKAN: Harus di-only agar masuk ke query update
        ]);

        // Tangani upload file audio
        if ($request->jenis === 'audio') {
            if ($request->hasFile('file_audio')) {
                if ($soal->file_audio && Storage::exists('public/audio_soas/' . $soal->file_audio)) {
                    Storage::delete('public/audio_soals/' . $soal->file_audio);
                }
                $file = $request->file('file_audio');
                $fileName = 'audio_soal_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/audio_soals', $fileName);
                $data['file_audio'] = $fileName;
            }
        } else {
            // Jika jenis diubah ke non-audio, hapus file audio jika ada
            if ($soal->file_audio && Storage::exists('public/audio_soals/' . $soal->file_audio)) {
                Storage::delete('public/audio_soals/' . $soal->file_audio);
            }
            $data['file_audio'] = null;
        }

        $soal->update($data);

        return back()->with('success', 'Data soal berhasil diperbarui!');
    }

    /**
     * MENGHAPUS SOAL
     */
    public function destroySoal($id)
    {
        $soal = Soal::findOrFail($id);

        if ($soal->file_audio && Storage::exists('public/audio_soals/' . $soal->file_audio)) {
            Storage::delete('public/audio_soals/' . $soal->file_audio);
        }

        $soal->delete();

        return back()->with('success', 'Soal berhasil dihapus dari bank soal!');
    }
    public function updateJuz(Request $request, Juz $juz)
{
    $request->validate([
        'nomor_juz' => 'required|integer|min:1|max:30|unique:juz,nomor,' . $juz->id,
    ]);

    $juz->update(['nomor' => $request->nomor_juz]);

    return back()->with('success', 'Juz berhasil diperbarui.');
}

public function destroyJuz(Juz $juz)
{
    // Sesuaikan: pastikan relasi surat/soal ikut terhapus (cascade) atau
    // tolak penghapusan kalau juz masih dipakai di quiz yang aktif.
    $juz->delete();

    return back()->with('success', 'Juz berhasil dihapus.');
}
}
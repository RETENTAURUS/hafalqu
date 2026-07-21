@extends('layouts.guru')

@section('title', 'Daftar & Kelola Quiz — HafalQU Guru')
@section('page_title', 'Kelola Quiz')
@section('page_subtitle', 'Pilih juz untuk membuat kuis baru atau pantau kuis yang sudah aktif.')

@section('content')
  
  {{-- ================= 1. PILIH JUZ UNTUK BUAT QUIZ BARU ================= --}}
  <div class="mb-10">
    <h2 class="text-base font-semibold text-slate-700 mb-4">Langkah 1: Pilih Juz Utama</h2>
    
    @if($daftarJuz->isEmpty())
      <div class="text-center py-12 text-slate-400 bg-white rounded-xl border border-slate-200">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-sm font-medium">Belum ada Juz yang tersedia.</p>
        <p class="text-xs mt-1">Tambahkan data Juz terlebih dahulu melalui menu Bank Soal.</p>
      </div>
    @else
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($daftarJuz as $juz)
        <a href="{{ route('guru.quiz.pilihSurat', $juz->id) }}"
           class="bg-white rounded-xl border border-slate-200 p-5 text-center hover:shadow-md hover:border-teal-400 transition-all duration-150 group">
          <div class="text-xl font-bold text-teal-700 group-hover:scale-105 transition-transform">Juz {{ $juz->nomor }}</div>
          <div class="text-xs text-slate-400 mt-1">{{ $juz->surats_count }} Surat</div>
        </a>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ================= 2. TABEL DAFTAR QUIZ LENGKAP ================= --}}
  <div class="mt-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-base font-semibold text-slate-700">Semua Quiz yang Telah Dibuat</h2>
    </div>

    @if($quizzes->isEmpty())
      <div class="text-center py-16 text-slate-400 bg-white rounded-xl border border-slate-200">
        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="12" cy="12" r="10"/>
          <polygon points="10 8 16 12 10 16 10 8"/>
        </svg>
        <p class="text-sm font-medium">Belum ada quiz yang dibuat.</p>
        <p class="text-xs text-slate-400 mt-0.5">Silakan pilih salah satu Juz di atas untuk memulai petualangan kuis pertama.</p>
      </div>
    @else
      <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-slate-200 bg-slate-50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide w-12">No</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Judul</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Juz</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipe Pengerjaan</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kelas</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Soal</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Durasi</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Batas</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide w-36">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @foreach($quizzes as $index => $quiz)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $index + 1 }}</td>
              <td class="px-5 py-3.5 font-medium text-slate-800">{{ $quiz->title }}</td>
              <td class="px-5 py-3.5 text-slate-600 font-medium">
                {{ $quiz->juz ? 'Juz ' . $quiz->juz->nomor : '-' }}
              </td>
              <td class="px-5 py-3.5">
                @if($quiz->tipe_pengerjaan === 'sekolah')
                  <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-teal-50 text-teal-700">
                    🏫 Di Sekolah
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full bg-amber-50 text-amber-700">
                    🏠 Di Rumah
                  </span>
                @endif
              </td>
              <td class="px-5 py-3.5 text-slate-500">{{ $quiz->kelas->nama ?? 'Semua Kelas' }}</td>
              <td class="px-5 py-3.5 text-center font-medium text-slate-700">{{ $quiz->soals_count }}</td>
              <td class="px-5 py-3.5 text-center text-slate-600">{{ $quiz->duration }} menit</td>
              <td class="px-5 py-3.5 text-center text-slate-600">{{ $quiz->attempt_limit == 0 ? '∞' : $quiz->attempt_limit }}</td>
              <td class="px-5 py-3.5 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  
                  {{-- Tombol Live Monitor (Hanya Tipe Sekolah yang SEDANG BERLANGSUNG) --}}
                  @if($quiz->tipe_pengerjaan === 'sekolah')
                    @php
                      $now = \Carbon\Carbon::now();
                      $isStarted = is_null($quiz->start_date) || $now->greaterThanOrEqualTo($quiz->start_date);
                      $isEnded = !is_null($quiz->end_date) && $now->greaterThan($quiz->end_date);
                      $isLive = $isStarted && !$isEnded;
                    @endphp

                    @if($isLive)
                      <a href="{{ route('guru.quiz.live', $quiz->id) }}" title="Pantau Live"
                        class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-colors relative">
                        <span class="absolute -top-1 -right-1 flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M15.5 12a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0z"/>
                          <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        </svg>
                      </a>
                    @endif
                  @endif

                  {{-- Tombol Lihat Hasil & Nilai Rekap (Berlaku untuk semua tipe kuis) --}}
                  <a href="{{ route('guru.quiz.nilai', $quiz->id) }}" title="Lihat Hasil & Nilai Siswa"
                     class="p-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                      <circle cx="9" cy="7" r="4"/>
                      <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                      <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                  </a>
                  
                  {{-- Form Hapus --}}
                  <form action="{{ route('guru.quiz.destroy', $quiz->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin hapus quiz ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Hapus"
                      class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-md transition-colors">
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                      </svg>
                    </button>
                  </form>

                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

@endsection
@extends('layouts.guru')

@section('title', 'Daftar & Kelola Quiz — HafalQU Guru')
@section('page_title', 'Kelola Quiz')
@section('page_subtitle', 'Pilih juz untuk membuat kuis baru atau pantau kuis yang sudah aktif.')

@section('content')

  {{-- ================= 1. PILIH JUZ UNTUK BUAT QUIZ BARU ================= --}}
  <div class="mb-8 sm:mb-10">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div>
        <h2 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight uppercase">Langkah 1: Pilih Juz Utama</h2>
        <p class="text-[11px] sm:text-xs text-slate-400">Pilih juz tempat soal berada untuk membuat kuis baru</p>
      </div>
    </div>
    
    @if($daftarJuz->isEmpty())
      <div class="text-center py-10 sm:py-12 px-4 text-slate-400 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
        <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2.5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-xs sm:text-sm font-bold text-slate-700">Belum ada Juz yang tersedia.</p>
        <p class="text-[11px] text-slate-400 mt-1">Tambahkan data Juz terlebih dahulu melalui menu Bank Soal.</p>
      </div>
    @else
      <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2.5 sm:gap-4">
        @foreach($daftarJuz as $juz)
        <a href="{{ route('guru.quiz.pilihSurat', $juz->id) }}"
           class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 p-3 sm:p-5 text-center shadow-sm hover:shadow-md hover:border-teal-500 active:scale-95 transition-all duration-150 group flex flex-col justify-center items-center aspect-square sm:aspect-auto">
          <div class="text-sm sm:text-xl font-bold text-[#115E59] group-hover:scale-105 transition-transform">Juz {{ $juz->nomor }}</div>
          <div class="text-[10px] sm:text-xs text-slate-400 mt-0.5 sm:mt-1 font-medium">{{ $juz->surats_count }} Surat</div>
        </a>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ================= 2. DAFTAR QUIZ LENGKAP ================= --}}
  <div class="mt-6 sm:mt-8">
    <div class="flex items-center justify-between mb-3 sm:mb-4">
      <div>
        <h2 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight uppercase">Semua Quiz yang Telah Dibuat</h2>
        <p class="text-[11px] sm:text-xs text-slate-400">Pantau dan kelola kuis yang sedang aktif maupun yang telah selesai</p>
      </div>
    </div>

    @if($quizzes->isEmpty())
      <div class="text-center py-12 sm:py-16 px-4 text-slate-400 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
        <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="12" cy="12" r="10"/>
          <polygon points="10 8 16 12 10 16 10 8"/>
        </svg>
        <p class="text-xs sm:text-sm font-bold text-slate-700">Belum ada quiz yang dibuat.</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Silakan pilih salah satu Juz di atas untuk memulai petualangan kuis pertama.</p>
      </div>
    @else

      {{-- MOBILE CARD VIEW (Tampil khusus di Layar HP) --}}
      <div class="block md:hidden space-y-3">
        @foreach($quizzes as $quiz)
          @php
            $now = \Carbon\Carbon::now();
            $isStarted = is_null($quiz->start_date) || $now->greaterThanOrEqualTo($quiz->start_date);
            $isEnded = !is_null($quiz->end_date) && $now->greaterThan($quiz->end_date);
            $isLive = ($quiz->tipe_pengerjaan === 'sekolah') && $isStarted && !$isEnded;
          @endphp
          <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm space-y-3">
            {{-- Header Card --}}
            <div class="flex items-start justify-between gap-2">
              <div>
                <h3 class="font-bold text-slate-800 text-xs sm:text-sm leading-snug">{{ $quiz->title }}</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">
                  {{ $quiz->juz ? 'Juz ' . $quiz->juz->nomor : 'Semua Juz' }} · {{ $quiz->kelas->nama ?? 'Semua Kelas' }}
                </p>
              </div>

              {{-- Tipe Badge --}}
              @if($quiz->tipe_pengerjaan === 'sekolah')
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-teal-50 text-teal-700 flex-shrink-0">
                  🏫 Sekolah
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 flex-shrink-0">
                  🏠 Rumah
                </span>
              @endif
            </div>

            {{-- Detail Metrics --}}
            <div class="grid grid-cols-3 gap-2 bg-slate-50/80 rounded-xl p-2.5 text-center text-[11px]">
              <div>
                <span class="text-slate-400 block text-[10px]">Soal</span>
                <span class="font-bold text-slate-700">{{ $quiz->soals_count }} Soal</span>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px]">Durasi</span>
                <span class="font-bold text-slate-700">{{ $quiz->duration }} min</span>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px]">Batas</span>
                <span class="font-bold text-slate-700">{{ $quiz->attempt_limit == 0 ? '∞' : $quiz->attempt_limit . 'x' }}</span>
              </div>
            </div>

            {{-- Footer Action Buttons --}}
            <div class="flex items-center justify-end gap-2 pt-1">
              @if($isLive)
                <a href="{{ route('guru.quiz.live', $quiz->id) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 text-red-700 hover:bg-red-100 font-semibold text-xs rounded-xl transition-colors relative">
                  <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                  </span>
                  <span>Live Monitor</span>
                </a>
              @endif

              <a href="{{ route('guru.quiz.nilai', $quiz->id) }}" 
                 class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-xs rounded-xl transition-colors">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>Nilai & Rekap</span>
              </a>

              <form action="{{ route('guru.quiz.destroy', $quiz->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Yakin hapus quiz ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors" title="Hapus Quiz">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                  </svg>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      {{-- DESKTOP TABLE VIEW (Tampil di Tablet & Laptop) --}}
      <div class="hidden md:block bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/80 text-slate-500 font-semibold">
              <th class="py-3 px-4 w-12 text-center">No</th>
              <th class="py-3 px-4">Judul Quiz</th>
              <th class="py-3 px-4">Juz</th>
              <th class="py-3 px-4">Tipe Pengerjaan</th>
              <th class="py-3 px-4">Kelas</th>
              <th class="py-3 px-4 text-center">Soal</th>
              <th class="py-3 px-4 text-center">Durasi</th>
              <th class="py-3 px-4 text-center">Batas</th>
              <th class="py-3 px-4 text-center w-36">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-700">
            @foreach($quizzes as $index => $quiz)
            @php
              $now = \Carbon\Carbon::now();
              $isStarted = is_null($quiz->start_date) || $now->greaterThanOrEqualTo($quiz->start_date);
              $isEnded = !is_null($quiz->end_date) && $now->greaterThan($quiz->end_date);
              $isLive = ($quiz->tipe_pengerjaan === 'sekolah') && $isStarted && !$isEnded;
            @endphp
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
              <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $quiz->title }}</td>
              <td class="py-3.5 px-4 text-slate-600 font-medium">
                {{ $quiz->juz ? 'Juz ' . $quiz->juz->nomor : '-' }}
              </td>
              <td class="py-3.5 px-4">
                @if($quiz->tipe_pengerjaan === 'sekolah')
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-teal-50 text-teal-700">
                    🏫 Di Sekolah
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700">
                    🏠 Di Rumah
                  </span>
                @endif
              </td>
              <td class="py-3.5 px-4 text-slate-500 font-medium">{{ $quiz->kelas->nama ?? 'Semua Kelas' }}</td>
              <td class="py-3.5 px-4 text-center font-bold text-slate-700">{{ $quiz->soals_count }}</td>
              <td class="py-3.5 px-4 text-center text-slate-600">{{ $quiz->duration }} min</td>
              <td class="py-3.5 px-4 text-center text-slate-600 font-semibold">{{ $quiz->attempt_limit == 0 ? '∞' : $quiz->attempt_limit }}</td>
              <td class="py-3.5 px-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  
                  {{-- Tombol Live Monitor --}}
                  @if($isLive)
                    <a href="{{ route('guru.quiz.live', $quiz->id) }}" title="Pantau Live"
                       class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors relative">
                      <span class="absolute -top-0.5 -right-0.5 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                      </span>
                      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15.5 12a3.5 3.5 0 11-7 0 3.5 3.5 0 017 0z"/>
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                      </svg>
                    </a>
                  @endif

                  {{-- Tombol Lihat Hasil & Nilai Rekap --}}
                  <a href="{{ route('guru.quiz.nilai', $quiz->id) }}" title="Lihat Hasil & Nilai Siswa"
                     class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl transition-colors">
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
                    <button type="submit" title="Hapus Quiz"
                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl transition-colors">
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
@extends('layouts.guru')

@section('title', 'Papan Peringkat — HafalQU Guru')
@section('page_title', 'Papan Peringkat')
@section('page_subtitle', 'Kelas ' . ($kelas->nama ?? 'Belum ada kelas'))

@section('content')
<div class="px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8 max-w-7xl mx-auto space-y-4 sm:space-y-6">

  {{-- Page Title --}}
  <div>
    <h1 class="text-lg sm:text-xl font-bold text-[#1a3a2e] flex flex-wrap items-center gap-2">
      <span>Papan Peringkat</span>
      @if($kelas)
        <span class="text-xs sm:text-sm font-medium text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
          Kelas {{ $kelas->nama }}
        </span>
      @endif
    </h1>
  </div>

  @if(!$kelas)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center space-y-1">
      <p class="color-red-800 font-semibold text-sm sm:text-base text-red-800">Anda belum memiliki kelas yang diampu.</p>
      <p class="text-slate-500 text-xs sm:text-sm">Hubungi administrator untuk menambahkan kelas.</p>
    </div>
  @elseif($leaderboard->isEmpty())
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 sm:p-12 text-center">
      <p class="text-slate-500 text-xs sm:text-sm">Belum ada siswa di kelas ini.</p>
    </div>
  @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-start">

      {{-- ===== LEADERBOARD KIRI ===== --}}
      <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-4 py-4 sm:px-6 sm:pt-5 sm:pb-3 border-b border-slate-100">
          <h2 class="text-sm sm:text-base font-bold text-[#1a3a2e]">
            🏆 Leaderboard Kelas {{ $kelas->nama }}
          </h2>
          <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">
            Top siswa berdasarkan akumulasi poin
          </p>
        </div>

        {{-- ===== PODIUM TOP 3 ===== --}}
        @if($leaderboard->count() > 0)
        <div class="px-2 pt-6 pb-2 sm:px-8 sm:pt-8 flex items-end justify-center gap-2 sm:gap-6 min-h-[200px] bg-gradient-to-b from-amber-50/20 to-transparent">

          {{-- RANK 2 --}}
          @if($leaderboard->count() >= 2)
          @php $s2 = $leaderboard[1]; @endphp
          <div class="flex flex-col items-center flex-1 max-w-[100px] sm:max-w-[130px]">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-slate-200 overflow-hidden border-2 sm:border-3 border-slate-400 flex items-center justify-center mb-1.5 shadow-sm flex-shrink-0">
              @if($s2->foto)
                <img src="{{ asset('storage/'.$s2->foto) }}" class="w-full h-full object-cover">
              @else
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
              @endif
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-slate-700 text-center w-full truncate mb-0.5">
              {{ $s2->name }}
            </p>
            <p class="text-[10px] sm:text-xs text-slate-500 font-medium mb-2">
              {{ number_format($s2->points) }} pts
            </p>
            <div class="w-full h-14 sm:h-20 bg-slate-400 rounded-t-xl flex items-center justify-center shadow-md">
              <span class="text-base sm:text-xl font-extrabold text-white">#2</span>
            </div>
          </div>
          @endif

          {{-- RANK 1 --}}
          @php $s1 = $leaderboard[0]; @endphp
          <div class="flex flex-col items-center flex-1 max-w-[110px] sm:max-w-[140px]">
            <span class="text-lg sm:text-2xl leading-none mb-1 animate-bounce">👑</span>
            <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-amber-100 overflow-hidden border-3 sm:border-4 border-amber-400 flex items-center justify-center mb-1.5 shadow-md flex-shrink-0 ring-4 ring-amber-400/20">
              @if($s1->foto)
                <img src="{{ asset('storage/'.$s1->foto) }}" class="w-full h-full object-cover">
              @else
                <svg class="w-7 h-7 sm:w-10 sm:h-10 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
              @endif
            </div>
            <p class="text-xs sm:text-sm font-bold text-[#1a3a2e] text-center w-full truncate mb-0.5">
              {{ $s1->name }}
            </p>
            <p class="text-[11px] sm:text-xs text-amber-600 font-bold mb-2">
              {{ number_format($s1->points) }} pts
            </p>
            <div class="w-full h-20 sm:h-28 bg-gradient-to-b from-amber-400 to-amber-600 rounded-t-xl flex items-center justify-center shadow-lg">
              <span class="text-xl sm:text-2xl font-black text-white">#1</span>
            </div>
          </div>

          {{-- RANK 3 --}}
          @if($leaderboard->count() >= 3)
          @php $s3 = $leaderboard[2]; @endphp
          <div class="flex flex-col items-center flex-1 max-w-[100px] sm:max-w-[130px]">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-amber-50 overflow-hidden border-2 sm:border-3 border-amber-700 flex items-center justify-center mb-1.5 shadow-sm flex-shrink-0">
              @if($s3->foto)
                <img src="{{ asset('storage/'.$s3->foto) }}" class="w-full h-full object-cover">
              @else
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
              @endif
            </div>
            <p class="text-[11px] sm:text-xs font-semibold text-slate-700 text-center w-full truncate mb-0.5">
              {{ $s3->name }}
            </p>
            <p class="text-[10px] sm:text-xs text-slate-500 font-medium mb-2">
              {{ number_format($s3->points) }} pts
            </p>
            <div class="w-full h-10 sm:h-14 bg-amber-700 rounded-t-xl flex items-center justify-center shadow-md">
              <span class="text-sm sm:text-lg font-extrabold text-white">#3</span>
            </div>
          </div>
          @endif

        </div>
        @endif

        {{-- ===== LIST #4 DST ===== --}}
        <div class="p-3 sm:p-5 space-y-1.5 sm:space-y-2 border-t border-slate-100">
          @foreach($leaderboard as $index => $siswa)
            @if($index >= 3)
            <div class="flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-100 transition-colors">

              {{-- Nomor --}}
              <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                {{ $index + 1 }}
              </div>

              {{-- Avatar --}}
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 border border-slate-200 flex items-center justify-center">
                @if($siswa->foto)
                  <img src="{{ asset('storage/'.$siswa->foto) }}" class="w-full h-full object-cover">
                @else
                  <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                  </svg>
                @endif
              </div>

              {{-- Nama --}}
              <span class="flex-1 font-medium text-slate-800 text-xs sm:text-sm truncate">
                {{ $siswa->name }}
              </span>

              {{-- Poin --}}
              <span class="font-bold text-xs sm:text-sm text-slate-700 flex-shrink-0">
                {{ number_format($siswa->points) }} <span class="text-[10px] sm:text-xs text-slate-400 font-normal">pts</span>
              </span>
            </div>
            @endif
          @endforeach
        </div>

        {{-- Footer total siswa --}}
        <div class="border-t border-slate-100 px-4 py-3 sm:px-6 bg-slate-50/50 flex justify-between items-center text-xs">
          <span class="text-slate-500 font-medium">Total Siswa Tampil</span>
          <span class="font-bold text-[#1a3a2e]">{{ $leaderboard->count() }} Siswa</span>
        </div>

      </div>

      {{-- ===== RINGKASAN KELAS KANAN ===== --}}
      <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden space-y-0">

        {{-- Header --}}
        <div class="px-4 py-3.5 sm:px-5 sm:py-4 border-b border-slate-100">
          <h3 class="text-xs sm:text-sm font-bold text-[#1a3a2e] flex items-center gap-1.5">
            <span>📊</span> Ringkasan Kelas
          </h3>
        </div>

        {{-- Item 1: Total Siswa --}}
        <div class="p-4 bg-emerald-50/50 border-b border-emerald-100/60 flex items-center justify-between">
          <span class="text-xs text-slate-500 font-medium">Total Siswa</span>
          <span class="text-base sm:text-lg font-black text-[#1a3a2e]">
            {{ $totalSiswa }}
          </span>
        </div>

        {{-- Item 2: Rata-rata Poin --}}
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs text-slate-500 font-medium">Rata-rata Poin</span>
          <span class="text-sm sm:text-base font-bold text-amber-600">
            {{ number_format($avgPoints) }}
          </span>
        </div>

        {{-- Item 3: Poin Tertinggi --}}
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs text-slate-500 font-medium">Poin Tertinggi</span>
          <span class="text-sm sm:text-base font-bold text-emerald-600">
            {{ number_format($highestPoints) }}
          </span>
        </div>

        {{-- Footer --}}
        <div class="p-3.5 bg-slate-50/50 text-center">
          <span class="text-[11px] text-slate-400">
            {{ $leaderboard->count() > 0 ? 'Data diperbarui secara otomatis' : 'Belum ada data' }}
          </span>
        </div>

      </div>

    </div>
  @endif
</div>
@endsection
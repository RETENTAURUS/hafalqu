@extends('layouts.guru')

@section('title', 'Hasil & Nilai Quiz — ' . $quiz->title)
@section('page_title', 'Hasil Kuis Siswa')
@section('page_subtitle', $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto px-0 sm:px-2">

  {{-- Top Bar Navigasi --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
    <div class="flex items-center gap-2">
      <span class="text-xs sm:text-sm font-bold text-[#115E59] bg-teal-50 px-2.5 py-1 rounded-md">📊 STATISTIK REKAP</span>
      <span class="text-[11px] sm:text-xs text-slate-400">Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</span>
    </div>
    <a href="{{ route('guru.quiz.index') }}" 
       class="inline-flex items-center gap-1 text-xs sm:text-sm text-slate-500 hover:text-slate-800 font-semibold transition-colors">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      <span>Kembali ke Daftar Kuis</span>
    </a>
  </div>

  {{-- Ringkasan Statistik Kuis --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-5 sm:mb-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
      <p class="text-2xl sm:text-3xl font-extrabold text-slate-800">{{ $summary['total_mengikuti'] ?? count($students) }}</p>
      <p class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Total Mengikuti</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
      <p class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ $summary['sedang_mengerjakan'] ?? 0 }}</p>
      <p class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Sedang Mengerjakan</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
      <p class="text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $summary['sudah_selesai'] ?? 0 }}</p>
      <p class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Sudah Selesai</p>
    </div>
  </div>

  {{-- Daftar Nilai Siswa --}}
  <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
    
    {{-- MOBILE CARD VIEW (Khusus Layar HP) --}}
    <div class="block sm:hidden divide-y divide-slate-100">
      @forelse($students as $index => $s)
        <div class="p-3.5 space-y-2.5">
          <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
              <span class="text-xs font-bold text-slate-400 w-5 flex-shrink-0">#{{ $index + 1 }}</span>
              <span class="font-bold text-slate-800 text-xs truncate">{{ $s->nama }}</span>
            </div>
            <div>
              @if(($s->status ?? '') === 'selesai')
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                  ✓ Selesai
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">
                  ✏️ Mengerjakan
                </span>
              @endif
            </div>
          </div>

          <div class="bg-slate-50 rounded-xl p-2.5 space-y-1.5">
            <div class="flex justify-between items-center text-[10px] text-slate-500 font-medium">
              <span>Progress ({{ $s->answered ?? 0 }}/{{ $s->total_soal ?? 0 }})</span>
              <span class="font-bold text-slate-700">Mulai: {{ $s->started_at ?? '—' }}</span>
            </div>
            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
              <div class="h-full bg-teal-600 rounded-full transition-all" style="width: {{ $s->percent ?? 0 }}%"></div>
            </div>
          </div>

          <div class="flex justify-between items-center pt-1 text-xs">
            <span class="text-slate-400 font-medium">Skor Akhir / Terbaik:</span>
            <span class="font-black text-sm text-slate-800">
              {{ !is_null($s->score) ? $s->score : '—' }}
            </span>
          </div>
        </div>
      @empty
        <div class="p-8 text-center text-slate-400">
          <span class="text-3xl block mb-2">📭</span>
          <p class="text-xs sm:text-sm font-semibold">Belum ada siswa yang mengerjakan kuis ini.</p>
        </div>
      @endforelse
    </div>

    {{-- DESKTOP TABLE VIEW (Khusus Tablet & Laptop) --}}
    <table class="hidden sm:table w-full text-left border-collapse text-xs sm:text-sm">
      <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">
        <tr>
          <th class="w-12 text-center py-3">No</th>
          <th class="py-3 px-4">Siswa</th>
          <th class="py-3 px-4">Progress</th>
          <th class="py-3 px-4">Status</th>
          <th class="py-3 px-4 text-center">Skor Terbaik</th>
          <th class="py-3 px-4">Waktu Mulai</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-slate-700">
        @forelse($students as $index => $s)
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $s->nama }}</td>
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-2.5">
                <div class="w-24 sm:w-28 h-2 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-[#115E59] rounded-full transition-all" style="width: {{ $s->percent ?? 0 }}%"></div>
                </div>
                <span class="text-xs text-slate-500 font-medium">{{ $s->answered ?? 0 }}/{{ $s->total_soal ?? 0 }}</span>
              </div>
            </td>
            <td class="py-3.5 px-4">
              @if(($s->status ?? '') === 'selesai')
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                  ✓ Selesai
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full">
                  ✏️ Mengerjakan
                </span>
              @endif
            </td>
            <td class="py-3.5 px-4 text-center font-bold text-slate-800 text-sm">
              {{ !is_null($s->score) ? $s->score : '—' }}
            </td>
            <td class="py-3.5 px-4 text-slate-400 text-xs font-medium">
              {{ $s->started_at ?? '—' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="py-12 text-center text-slate-400">
              <span class="text-3xl block mb-2">📭</span>
              <p class="text-xs sm:text-sm font-semibold">Belum ada siswa yang mengerjakan kuis ini.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

  </div>

</div>
@endsection
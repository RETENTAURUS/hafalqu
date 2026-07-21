@extends('layouts.guru')

@section('title', 'Hasil & Nilai Quiz — ' . $quiz->title)
@section('page_title', 'Hasil Kuis Siswa')
@section('page_subtitle', $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto">

  {{-- Top Bar navigasi kembali --}}
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-2">
      <span class="text-sm font-semibold text-teal-600">📊 STATIS</span>
      <span class="text-xs text-slate-400">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</span>
    </div>
    <a href="{{ route('guru.quiz.index') }}" class="text-sm text-slate-500 hover:text-slate-700 font-medium">← Kembali ke Daftar Kuis</a>
  </div>

  {{-- Ringkasan Statistik Kuis --}}
  <div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
      <p class="text-2xl font-bold text-slate-800">{{ $summary['total_mengikuti'] ?? count($students) }}</p>
      <p class="text-xs text-slate-500 mt-0.5">Total Mengikuti</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
      <p class="text-2xl font-bold text-amber-600">{{ $summary['sedang_mengerjakan'] ?? 0 }}</p>
      <p class="text-xs text-slate-500 mt-0.5">Sedang Mengerjakan</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
      <p class="text-2xl font-bold text-emerald-600">{{ $summary['sudah_selesai'] ?? 0 }}</p>
      <p class="text-xs text-slate-500 mt-0.5">Sudah Selesai</p>
    </div>
  </div>

  {{-- Daftar Nilai Siswa --}}
  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide border-b border-slate-200">
        <tr>
          <th width="50" class="text-center py-3">No</th>
          <th class="text-left px-4 py-3">Siswa</th>
          <th class="text-left px-4 py-3">Progress</th>
          <th class="text-left px-4 py-3">Status</th>
          <th class="text-center px-4 py-3">Skor Terbaik</th>
          <th class="text-left px-4 py-3">Waktu Mulai</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($students as $index => $s)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="text-center text-slate-400 text-xs">{{ $index + 1 }}</td>
            <td class="px-4 py-3 font-medium text-slate-800">{{ $s->nama }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                {{-- Progress Bar --}}
                <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-teal-500 rounded-full transition-all" style="width: {{ $s->percent }}%"></div>
                </div>
                <span class="text-xs text-slate-500">{{ $s->answered }}/{{ $s->total_soal }}</span>
              </div>
            </td>
            <td class="px-4 py-3">
              @if(($s->status ?? '') === 'selesai')
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                  ✓ Selesai
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                  ✏️ Mengerjakan
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-center font-bold text-slate-700">
              {{ !is_null($s->score) ? $s->score : '—' }}
            </td>
            <td class="px-4 py-3 text-slate-400 text-xs">
              {{ $s->started_at ?? '—' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-12 text-center text-slate-400">
              <span class="text-2xl">📭</span>
              <p class="text-sm font-medium mt-2">Belum ada siswa yang mengerjakan kuis ini.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
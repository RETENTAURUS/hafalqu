@extends('layouts.siswa')

@section('title', 'Konfirmasi Quiz — HafalQU')
@section('page_title', 'Konfirmasi')
@section('page_subtitle', 'Pastikan Anda siap memulai quiz')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm text-center">

        <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polygon points="10 8 16 12 10 16 10 8"/>
            </svg>
        </div>

        <h3 class="text-xl font-bold text-slate-800">{{ $quiz->title }}</h3>

        <div class="flex items-center justify-center gap-2 mt-2">
            <span class="text-xs font-semibold px-3 py-1 rounded-full
                {{ $quiz->tipe_pengerjaan === 'sekolah' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                {{ $quiz->tipe_pengerjaan === 'sekolah' ? '🏫 Dikerjakan di Sekolah' : '🏠 Dikerjakan di Rumah' }}
            </span>
        </div>

        <p class="text-sm text-slate-500 mt-2">Jumlah soal: {{ $totalSoal }} soal</p>

        {{-- Ringkasan data quiz — semua diambil langsung dari database, tidak ada nilai acak/tebakan --}}
        <div class="grid grid-cols-2 gap-4 mt-6 text-left bg-slate-50 rounded-xl p-4">
            <div>
                <p class="text-xs text-slate-400">Waktu Pengerjaan</p>
                <p class="font-semibold text-slate-700">{{ $quiz->duration ?? 30 }} Menit</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Poin Maksimal</p>
                <p class="font-semibold text-slate-700">{{ $poinMaksimal }} Poin</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Nilai Minimum Lulus</p>
                <p class="font-semibold text-slate-700">{{ $quiz->passing_score ?? 70 }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Batas Percobaan</p>
                @if ($maxAttempts === null)
                    <p class="font-semibold text-slate-700">Tidak terbatas</p>
                @else
                    <p class="font-semibold text-slate-700">
                        {{ $sisaPercobaan }} dari {{ $maxAttempts }} tersisa
                    </p>
                @endif
            </div>
        </div>

        @if ($maxAttempts !== null && $sisaPercobaan <= 1)
            <p class="text-xs text-amber-600 font-medium mt-3">
                ⚠️ Ini {{ $sisaPercobaan === 1 ? 'kesempatan terakhirmu' : 'sudah tidak ada kesempatan lagi' }} untuk quiz ini. Kerjakan dengan teliti!
            </p>
        @endif

        <div class="mt-6 flex gap-3 justify-center">
            <a href="{{ route('siswa.quiz.index') }}"
               class="px-6 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition">
                Batal
            </a>

            @if ($maxAttempts !== null && $sisaPercobaan <= 0)
                <span class="px-6 py-2 bg-slate-200 text-slate-400 text-sm font-medium rounded-lg cursor-not-allowed">
                    Batas Percobaan Habis
                </span>
            @else
                <a href="{{ route('siswa.quiz.start', $quiz->id) }}"
                   class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                    Mulai Quiz
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
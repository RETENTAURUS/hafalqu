@extends('layouts.guru')

@section('title', 'Pilih Surat — Kelola Quiz')
@section('page_title', 'Juz ' . $juz->nomor)
@section('page_subtitle', 'Pilih surat yang akan dimasukkan ke quiz')

@section('content')
<form action="{{ route('guru.quiz.pilihSurat', $juz->id) }}" method="GET" id="pilihSuratForm">
    <input type="hidden" name="juz_id" value="{{ $juz->id }}">
    
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-600">Pilih surat yang ingin dimasukkan ke dalam quiz. <span class="text-slate-400 text-xs">(minimal 1 surat)</span></p>
            <span class="text-sm text-slate-500" id="selectedCount">0 surat dipilih</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($juz->surats as $surat)
            <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-all duration-150 {{ in_array($surat->id, session('selected_surat_ids', [])) ? 'border-teal-500 bg-teal-50' : '' }}">
                <input type="checkbox" name="surat_ids[]" value="{{ $surat->id }}"
                       class="surat-checkbox mt-1 w-4 h-4 text-teal-600 rounded focus:ring-teal-500"
                       onchange="updateSelectedCount()"
                       {{ in_array($surat->id, session('selected_surat_ids', [])) ? 'checked' : '' }}>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-slate-800">{{ $surat->nama_surat }}</h4>
                        <span class="text-xs text-slate-400">{{ $surat->nomor_surat }}</span>
                    </div>
                    <p class="text-xs text-slate-400">{{ $surat->total_ayat }} ayat · {{ $surat->soals_count }} soal</p>
                </div>
            </label>
            @endforeach
        </div>

        @if($juz->surats->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <p class="text-sm">Belum ada surat di Juz ini.</p>
                <a href="{{ route('guru.soal.showJuz', $juz->id) }}" class="text-teal-600 text-sm hover:underline mt-2 inline-block">Tambahkan surat</a>
            </div>
        @endif
    </div>

    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('guru.quiz.pilihJuz') }}" class="px-6 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
            Kembali ke Daftar Juz
        </a>
        <button type="submit" id="lanjutBtn" disabled
                class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
            Lanjut Konfigurasi ({{ count(session('selected_surat_ids', [])) }} surat)
        </button>
    </div>
</form>

<script>
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.surat-checkbox:checked');
        const count = checkboxes.length;
        document.getElementById('selectedCount').textContent = count + ' surat dipilih';
        document.getElementById('lanjutBtn').disabled = count === 0;
        document.getElementById('lanjutBtn').innerHTML = 'Lanjut Konfigurasi (' + count + ' surat)';
    }
    // Jalankan saat load untuk inisialisasi
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
    });
</script>
@endsection
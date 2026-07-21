@extends('layouts.guru')

@section('title', 'Pilih Surat — Kelola Quiz')
@section('page_title', 'Juz ' . $juz->nomor)
@section('page_subtitle', 'Pilih surat yang akan dimasukkan ke quiz')

@section('content')
<form action="{{ route('guru.quiz.pilihSurat', $juz->id) }}" method="GET" id="pilihSuratForm">
    <input type="hidden" name="juz_id" value="{{ $juz->id }}">
    
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
        {{-- Header Status & Quick Select --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
            <div>
                <p class="text-xs sm:text-sm font-semibold text-slate-700">Pilih surat yang ingin dimasukkan ke dalam quiz.</p>
                <p class="text-[11px] text-slate-400">Minimal pilih 1 surat untuk melanjutkan ke tahap konfigurasi.</p>
            </div>
            
            <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-full flex-shrink-0" id="selectedCount">
                    0 surat dipilih
                </span>
                @if(!$juz->surats->isEmpty())
                    <button type="button" onclick="toggleSelectAll(this)" class="text-xs font-bold text-teal-700 hover:underline flex-shrink-0">
                        Pilih Semua
                    </button>
                @endif
            </div>
        </div>

        {{-- Grid Daftar Surat --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($juz->surats as $surat)
            @php $isChecked = in_array($surat->id, session('selected_surat_ids', [])); @endphp
            <label id="card_surat_{{ $surat->id }}" 
                   class="surat-card flex items-start gap-3 p-3.5 sm:p-4 border rounded-2xl cursor-pointer transition-all duration-150 {{ $isChecked ? 'border-teal-500 bg-teal-50/60 shadow-sm' : 'border-slate-200/80 hover:bg-slate-50' }}">
                <input type="checkbox" name="surat_ids[]" value="{{ $surat->id }}"
                       class="surat-checkbox mt-1 w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500"
                       onchange="handleCheckboxChange(this)"
                       {{ $isChecked ? 'checked' : '' }}>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <h4 class="font-bold text-slate-800 text-xs sm:text-sm truncate">{{ $surat->nama_surat }}</h4>
                        <span class="text-[10px] sm:text-xs font-medium text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-md flex-shrink-0">
                            Surah {{ $surat->nomor_surat }}
                        </span>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-400 mt-1 font-medium">
                        {{ $surat->total_ayat }} ayat · <span class="text-teal-700 font-semibold">{{ $surat->soals_count }} soal</span>
                    </p>
                </div>
            </label>
            @endforeach
        </div>

        {{-- Empty State --}}
        @if($juz->surats->isEmpty())
            <div class="text-center py-10 px-4 text-slate-400 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200">
                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-xs sm:text-sm font-semibold text-slate-700">Belum ada surat di Juz ini.</p>
                <a href="{{ route('guru.soal.showJuz', $juz->id) }}" class="inline-flex items-center gap-1 text-teal-600 text-xs font-bold hover:underline mt-2">
                    <span>Tambahkan surat</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @endif
    </div>

    {{-- Bottom Action Buttons --}}
    <div class="mt-4 sm:mt-6 flex flex-col-reverse sm:flex-row justify-end gap-2.5">
        <a href="{{ route('guru.quiz.pilihJuz') }}" 
           class="w-full sm:w-auto text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
            Kembali ke Daftar Juz
        </a>
        <button type="submit" id="lanjutBtn" disabled
                class="w-full sm:w-auto px-6 py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
            Lanjut Konfigurasi (0 surat)
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.surat-checkbox:checked');
        const count = checkboxes.length;
        
        document.getElementById('selectedCount').textContent = count + ' surat dipilih';
        
        const btn = document.getElementById('lanjutBtn');
        btn.disabled = count === 0;
        btn.textContent = 'Lanjut Konfigurasi (' + count + ' surat)';
    }

    function handleCheckboxChange(checkbox) {
        const card = document.getElementById('card_surat_' + checkbox.value);
        if (card) {
            if (checkbox.checked) {
                card.classList.add('border-teal-500', 'bg-teal-50/60', 'shadow-sm');
                card.classList.remove('border-slate-200/80');
            } else {
                card.classList.remove('border-teal-500', 'bg-teal-50/60', 'shadow-sm');
                card.classList.add('border-slate-200/80');
            }
        }
        updateSelectedCount();
    }

    function toggleSelectAll(btn) {
        const checkboxes = document.querySelectorAll('.surat-checkbox');
        const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => {
            cb.checked = !isAllChecked;
            handleCheckboxChange(cb);
        });

        btn.textContent = isAllChecked ? 'Pilih Semua' : 'Batal Pilih Semua';
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
    });
</script>
@endsection
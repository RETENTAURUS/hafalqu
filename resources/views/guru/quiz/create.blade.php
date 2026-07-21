@extends('layouts.guru')

@section('title', 'Buat Quiz — Kelola Quiz')
@section('page_title', 'Buat Quiz Baru')
@section('page_subtitle', 'Pilih soal dari ' . $surat->nama_surat)

@section('content')
  <div class="mb-4">
    <a href="{{ route('guru.quiz.pilihSurat', $surat->juz_id) }}" 
       class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      <span>Kembali ke Daftar Surat</span>
    </a>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
    <form action="{{ route('guru.quiz.store') }}" method="POST" class="space-y-5 sm:space-y-6">
      @csrf
      <input type="hidden" name="surat_id" value="{{ $surat->id }}">

      {{-- Judul Quiz --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Judul Quiz <span class="text-red-500">*</span></label>
        <input type="text" name="title" placeholder="cth. Quiz Surah An-Naba'" required
               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
      </div>

      {{-- Daftar Soal --}}
      <div>
        <div class="flex items-center justify-between mb-2 sm:mb-3">
          <p class="text-xs sm:text-sm font-semibold text-slate-700">Pilih Soal (Minimal 1 soal)</p>
          @if(!$surat->soals->isEmpty())
            <button type="button" onclick="toggleSelectAll(this)" class="text-xs font-semibold text-teal-700 hover:underline">
              Pilih Semua
            </button>
          @endif
        </div>

        @if($surat->soals->isEmpty())
          <div class="text-center py-8 px-4 text-slate-400 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs sm:text-sm font-semibold text-slate-700">Belum ada soal di surat ini.</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Tambahkan soal terlebih dahulu untuk dapat membuat quiz.</p>
            <a href="{{ route('guru.soal.showJuz', ['juz_id' => $surat->juz_id, 'surat_id' => $surat->id]) }}"
               class="inline-flex items-center gap-1 text-teal-600 text-xs font-bold hover:underline mt-3">
              <span>Tambahkan Soal</span>
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        @else
          <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1 sm:pr-2">
            @foreach($surat->soals as $soal)
            <label class="flex items-start gap-3 p-3 border border-slate-200/80 rounded-xl hover:bg-slate-50/80 active:bg-slate-100/80 cursor-pointer transition-colors">
              <input type="checkbox" name="soal_ids[]" value="{{ $soal->id }}"
                     class="soal-checkbox mt-1 w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500">
              <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-medium text-slate-800 leading-relaxed">{{ $soal->pertanyaan }}</p>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span class="text-[10px] sm:text-xs font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">{{ ucfirst($soal->jenis) }}</span>
                  <span class="text-[10px] sm:text-xs font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md">{{ $soal->kesulitan ?? 'Mudah' }}</span>
                  <span class="text-[10px] sm:text-xs font-medium text-slate-400 self-center">{{ $soal->poin ?? 100 }} poin</span>
                </div>
              </div>
            </label>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Action Buttons --}}
      <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.quiz.pilihSurat', $surat->juz_id) }}"
           class="w-full sm:w-auto text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
          Batal
        </a>
        <button type="submit"
                class="w-full sm:w-auto px-5 py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Simpan Quiz
        </button>
      </div>
    </form>
  </div>
@endsection

@section('scripts')
<script>
  function toggleSelectAll(btn) {
    const checkboxes = document.querySelectorAll('.soal-checkbox');
    const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => cb.checked = !isAllChecked);
    btn.textContent = isAllChecked ? 'Pilih Semua' : 'Batal Pilih Semua';
  }
</script>
@endsection
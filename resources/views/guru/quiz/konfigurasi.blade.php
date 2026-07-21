@extends('layouts.guru')

@section('title', 'Konfigurasi Quiz — HafalQU Guru')
@section('page_title', 'Konfigurasi Quiz')
@section('page_subtitle', 'Atur jumlah dan jenis soal untuk setiap surat')

@section('header_actions')
  <a href="{{ route('guru.quiz.pilihSurat', session('selected_juz_id', 1)) }}" 
     class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-600 hover:bg-slate-700 active:bg-slate-800 text-white text-xs sm:text-sm font-semibold px-4 py-2.5 sm:py-2 rounded-xl transition-colors shadow-sm">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path d="M19 12H5M12 5l-7 7 7 7"/>
    </svg>
    <span>Kembali Pilih Surat</span>
  </a>
@endsection

@section('content')
<div class="flex flex-col-reverse lg:flex-row gap-5 lg:gap-6 items-start">
  
  <!-- Form Konfigurasi (Kiri di Desktop, Bawah di Mobile) -->
  <div class="w-full flex-1 space-y-4 sm:space-y-6">
    <form action="{{ route('guru.quiz.simpanKonfigurasi') }}" method="POST" id="konfigurasiForm" class="space-y-4 sm:space-y-6">
      @csrf
      
      @foreach($surats as $surat)
      <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
        <div class="flex items-start justify-between mb-4 pb-3 border-b border-slate-100">
          <div>
            <h3 class="font-bold text-slate-800 text-base sm:text-lg">{{ $surat->nama_surat }}</h3>
            <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Surah {{ $surat->nomor_surat }} · {{ $surat->soals_count }} soal tersedia</p>
          </div>
          <span class="bg-teal-50 text-[#115E59] text-xs font-semibold px-3 py-1 rounded-full flex-shrink-0">
            <span id="badge_jumlah_{{ $surat->id }}">{{ $config[$surat->id]['jumlah'] ?? 5 }}</span> soal
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Jumlah Soal -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Jumlah Soal</label>
            <div class="flex items-center gap-2">
              <button type="button" onclick="ubahJumlah({{ $surat->id }}, -1)" 
                      class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 active:scale-95 flex items-center justify-center font-bold text-slate-600 transition-all text-sm">
                −
              </button>
              <input type="number" name="konfigurasi[{{ $surat->id }}][jumlah]" 
                     id="jumlah_{{ $surat->id }}"
                     value="{{ $config[$surat->id]['jumlah'] ?? 5 }}"
                     min="1" max="{{ $surat->soals_count }}"
                     class="w-16 text-center border border-slate-200 rounded-xl px-2 py-2 text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <button type="button" onclick="ubahJumlah({{ $surat->id }}, 1)" 
                      class="w-9 h-9 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 active:scale-95 flex items-center justify-center font-bold text-slate-600 transition-all text-sm">
                +
              </button>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Maksimal {{ $surat->soals_count }} soal</p>
          </div>

          <!-- Jenis Soal -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Jenis Soal</label>
            <div class="space-y-2">
              @foreach(['melanjutkan' => 'Melanjutkan Ayat', 'mengisi' => 'Isi Kosong', 'pengetahuan' => 'Pengetahuan', 'audio' => 'Audio'] as $key => $label)
              <label class="flex items-center gap-2 text-xs sm:text-sm text-slate-700 font-medium cursor-pointer">
                <input type="checkbox" name="konfigurasi[{{ $surat->id }}][jenis][]" value="{{ $key }}"
                       class="w-4 h-4 text-teal-600 border-slate-300 rounded focus:ring-teal-500"
                       {{ in_array($key, $config[$surat->id]['jenis'] ?? ['melanjutkan','mengisi','pengetahuan','audio']) ? 'checked' : '' }}>
                <span>{{ $label }}</span>
              </label>
              @endforeach
            </div>
          </div>

          <!-- Tingkat Kesulitan -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Tingkat Kesulitan</label>
            <select name="konfigurasi[{{ $surat->id }}][kesulitan]" 
                    class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <option value="semua" {{ ($config[$surat->id]['kesulitan'] ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua</option>
              <option value="mudah" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'mudah' ? 'selected' : '' }}>Mudah</option>
              <option value="sedang" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'sedang' ? 'selected' : '' }}>Sedang</option>
              <option value="sulit" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'sulit' ? 'selected' : '' }}>Sulit</option>
            </select>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Tombol Submit (Mobile & Desktop) -->
      <div class="flex flex-col-reverse sm:flex-row gap-2.5 pt-2">
        <a href="{{ route('guru.quiz.pilihSurat', session('selected_juz_id', 1)) }}"
           class="w-full sm:w-auto text-center px-6 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
          Kembali
        </a>
        <button type="submit" 
                class="w-full sm:w-auto px-6 py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Lanjut Quiz
        </button>
      </div>
    </form>
  </div>

  <!-- Ringkasan Quiz (Atas di Mobile, Kanan di Desktop) -->
  <div class="w-full lg:w-80 xl:w-96 flex-shrink-0 lg:sticky lg:top-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm space-y-3">
      <h4 class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider">Ringkasan Quiz</h4>
      
      <div class="space-y-2.5 max-h-60 lg:max-h-80 overflow-y-auto pr-1">
        @foreach($surats as $surat)
        <div class="flex justify-between items-center text-xs sm:text-sm py-1 border-b border-slate-100">
          <span class="font-medium text-slate-700">{{ $surat->nama_surat }}</span>
          <span class="font-bold text-teal-700" id="ringkasan_{{ $surat->id }}">
            {{ $config[$surat->id]['jumlah'] ?? 5 }} Soal
          </span>
        </div>
        @endforeach
      </div>

      <div class="flex justify-between items-center pt-3 border-t-2 border-teal-500/20">
        <span class="font-bold text-slate-800 text-xs sm:text-sm">Total Soal</span>
        <span class="font-black text-teal-700 text-base sm:text-lg" id="totalSoal">
          {{ collect($surats)->sum(function($s) use ($config) { return $config[$s->id]['jumlah'] ?? 5; }) }}
        </span>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  function ubahJumlah(suratId, delta) {
    const input = document.getElementById('jumlah_' + suratId);
    let val = parseInt(input.value) || 0;
    val = Math.max(1, Math.min(parseInt(input.max) || 99, val + delta));
    input.value = val;
    input.dispatchEvent(new Event('change'));
  }

  document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[name*="[jumlah]"]');
    inputs.forEach(input => {
      input.addEventListener('change', function() {
        const suratId = this.name.match(/\[(\d+)\]/)[1];
        const val = this.value || 0;
        
        // Update badge di header card
        const badgeEl = document.getElementById('badge_jumlah_' + suratId);
        if(badgeEl) badgeEl.textContent = val;

        // Update ringkasan di samping
        const ringkasanEl = document.getElementById('ringkasan_' + suratId);
        if(ringkasanEl) ringkasanEl.textContent = val + ' Soal';

        updateTotal();
      });
    });

    function updateTotal() {
      let total = 0;
      document.querySelectorAll('input[name*="[jumlah]"]').forEach(inp => {
        total += parseInt(inp.value) || 0;
      });
      document.getElementById('totalSoal').textContent = total;
    }
  });
</script>
@endsection
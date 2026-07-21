@extends('layouts.guru')

@section('title', 'Konfigurasi Quiz — HafalQU Guru')
@section('page_title', 'Konfigurasi Quiz')
@section('page_subtitle', 'Atur jumlah dan jenis soal untuk setiap surat')

@section('header_actions')
  <a href="{{ route('guru.quiz.pilihSurat', session('selected_juz_id', 1)) }}" 
     class="flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path d="M19 12H5M12 5l-7 7 7 7"/>
    </svg>
    Kembali Pilih Surat
  </a>
@endsection

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
  
  <!-- Form Konfigurasi (kiri) -->
  <div class="flex-1 space-y-6">
    <form action="{{ route('guru.quiz.simpanKonfigurasi') }}" method="POST" id="konfigurasiForm">
      @csrf
      
      @foreach($surats as $surat)
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="font-semibold text-slate-800 text-lg">{{ $surat->nama_surat }}</h3>
            <p class="text-xs text-slate-400">Surah {{ $surat->nomor_surat }} · {{ $surat->soals_count }} soal tersedia</p>
          </div>
          <span class="bg-teal-50 text-teal-600 text-xs font-medium px-3 py-1 rounded-full">
            {{ $config[$surat->id]['jumlah'] ?? 0 }} soal
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Jumlah Soal -->
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Jumlah Soal</label>
            <div class="flex items-center gap-2">
              <button type="button" onclick="ubahJumlah({{ $surat->id }}, -1)" 
                      class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-100 flex items-center justify-center">−</button>
              <input type="number" name="konfigurasi[{{ $surat->id }}][jumlah]" 
                     id="jumlah_{{ $surat->id }}"
                     value="{{ $config[$surat->id]['jumlah'] ?? 5 }}"
                     min="1" max="{{ $surat->soals_count }}"
                     class="w-16 text-center border border-slate-200 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-teal-500">
              <button type="button" onclick="ubahJumlah({{ $surat->id }}, 1)" 
                      class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-100 flex items-center justify-center">+</button>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Maksimal {{ $surat->soals_count }}</p>
          </div>

          <!-- Jenis Soal -->
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Jenis Soal</label>
            <div class="space-y-1.5">
              @foreach(['melanjutkan' => 'Melanjutkan Ayat', 'mengisi' => 'Isi Kosong', 'pengetahuan' => 'Pengetahuan', 'audio' => 'Audio'] as $key => $label)
              <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="konfigurasi[{{ $surat->id }}][jenis][]" value="{{ $key }}"
                       class="w-3.5 h-3.5 text-teal-600 rounded focus:ring-teal-500"
                       {{ in_array($key, $config[$surat->id]['jenis'] ?? ['melanjutkan','mengisi','pengetahuan','audio']) ? 'checked' : '' }}>
                {{ $label }}
              </label>
              @endforeach
            </div>
          </div>

          <!-- Tingkat Kesulitan -->
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Tingkat Kesulitan</label>
            <select name="konfigurasi[{{ $surat->id }}][kesulitan]" 
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
              <option value="semua" {{ ($config[$surat->id]['kesulitan'] ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua</option>
              <option value="mudah" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'mudah' ? 'selected' : '' }}>Mudah</option>
              <option value="sedang" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'sedang' ? 'selected' : '' }}>Sedang</option>
              <option value="sulit" {{ ($config[$surat->id]['kesulitan'] ?? '') == 'sulit' ? 'selected' : '' }}>Sulit</option>
            </select>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Tombol Submit -->
      <div class="flex gap-3 pt-2">
        <a href="{{ route('guru.quiz.pilihSurat', session('selected_juz_id', 1)) }}"
           class="px-6 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
          Kembali
        </a>
        <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
          Lanjut Quiz
        </button>
      </div>
    </form>
  </div>

  <!-- Ringkasan Quiz (kanan) -->
  <div class="lg:w-80 xl:w-96 flex-shrink-0">
    <div class="bg-white rounded-xl border border-slate-200 p-5 sticky top-6">
      <h4 class="font-semibold text-slate-800 text-sm mb-4">Ringkasan Quiz</h4>
      <div class="space-y-3">
        @foreach($surats as $surat)
        <div class="flex justify-between items-center border-b border-slate-100 pb-2">
          <span class="text-sm font-medium text-slate-700">{{ $surat->nama_surat }}</span>
          <span class="text-sm font-bold text-teal-600" id="ringkasan_{{ $surat->id }}">
            {{ $config[$surat->id]['jumlah'] ?? 0 }} Soal
          </span>
        </div>
        @endforeach
        <div class="flex justify-between items-center pt-2 border-t-2 border-teal-200">
          <span class="font-semibold text-slate-800">Total Soal</span>
          <span class="font-bold text-teal-700 text-lg" id="totalSoal">
            {{ collect($surats)->sum(function($s) use ($config) { return $config[$s->id]['jumlah'] ?? 0; }) }}
          </span>
        </div>
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
        document.getElementById('ringkasan_' + suratId).textContent = this.value + ' Soal';
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
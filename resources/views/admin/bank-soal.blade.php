@extends('layouts.admin')

@section('title', 'HafalQU — Bank Soal Hafalan')

@section('breadcrumb')
  Bank Soal Hafalan
@endsection

@section('header_actions')
  {{-- Tidak ada tombol aksi di header untuk halaman ini --}}
@endsection

@section('styles')
  <style>
    /* Custom Box Shadows & Rounding matching the uploaded style */
    .custom-card {
      box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .juz-box {
      box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease;
    }
    @media (min-width: 640px) {
      .juz-box {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
      }
    }
    .juz-box:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
  </style>
@endsection

@section('content')
  <div class="space-y-4 sm:space-y-6">

    <!-- Daftar Juz -->
    <div class="bg-white rounded-2xl border border-slate-200/80 custom-card p-4 sm:p-6">
      <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
          <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Daftar Juz</h2>
          <p class="text-[11px] sm:text-xs text-slate-400">Pilih juz untuk mengelola soal</p>
        </div>
        <button onclick="openTambahJuzModal()" 
          class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs font-semibold px-3 py-2 rounded-xl sm:rounded-lg transition-colors shadow-sm active:scale-95">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          <span>Tambah Juz</span>
        </button>
      </div>

      <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2.5 sm:gap-4">
        @forelse($daftarJuz as $juz)
          <a href="{{ route('admin.soal.showJuz', $juz->id) }}" 
             class="juz-box bg-white border border-slate-200 hover:border-teal-500 rounded-xl sm:rounded-2xl p-3 sm:p-6 flex items-center justify-center aspect-square cursor-pointer active:scale-95 transition-all">
            <span class="text-slate-700 font-bold text-xs sm:text-base tracking-wide text-center">Juz {{ $juz->nomor }}</span>
          </a>
        @empty
          {{-- Fallback dummy jika tidak ada juz --}}
          @foreach([30, 1, 2, 3] as $dummy)
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-xl sm:rounded-2xl p-3 sm:p-6 flex flex-col items-center justify-center aspect-square text-center">
              <span class="text-slate-400 font-medium text-xs sm:text-base">Juz {{ $dummy }}</span>
              <span class="text-[9px] sm:text-[10px] text-slate-400 mt-0.5 sm:mt-1">(Belum)</span>
            </div>
          @endforeach
        @endforelse
      </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-5">
      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-4 sm:p-6 flex flex-col justify-between sm:aspect-[4/3] space-y-2 sm:space-y-0">
        <span class="text-3xl sm:text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalJuz }}</span>
        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 tracking-wide">Total Juz</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-4 sm:p-6 flex flex-col justify-between sm:aspect-[4/3] space-y-2 sm:space-y-0">
        <span class="text-3xl sm:text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalSurat }}</span>
        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 tracking-wide">Total Surat</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-4 sm:p-6 flex flex-col justify-between sm:aspect-[4/3] space-y-2 sm:space-y-0">
        <span class="text-3xl sm:text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalSoal }}</span>
        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 tracking-wide">Total Soal</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-4 sm:p-6 flex flex-col justify-between sm:aspect-[4/3] space-y-2 sm:space-y-0">
        <span class="text-3xl sm:text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $soalAudio }}</span>
        <span class="text-[11px] sm:text-xs font-semibold text-slate-400 tracking-wide">Soal Audio</span>
      </div>
    </div>

  </div>
@endsection

@section('modals')
  <!-- Modal Tambah Juz -->
  <div id="tambahJuzModal" 
       class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
       style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full sm:max-w-sm rounded-t-2xl sm:rounded-2xl shadow-xl overflow-hidden p-5 sm:p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-slate-800">Tambah Juz Baru</h3>
        <button onclick="closeTambahJuzModal()" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form action="{{ route('admin.soal.storeJuz') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Juz</label>
          <input type="number" min="1" max="30" name="nomor_juz" placeholder="Masukkan nomor (1-30)" required
            class="w-full border border-slate-200 rounded-xl sm:rounded-lg px-3 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeTambahJuzModal()" 
            class="flex-1 border border-slate-200 text-slate-600 text-xs font-semibold py-2.5 sm:py-2 rounded-xl sm:rounded-lg hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" 
            class="flex-1 bg-[#115E59] text-white text-xs font-semibold py-2.5 sm:py-2 rounded-xl sm:rounded-lg hover:bg-teal-800 shadow-sm">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function openTambahJuzModal() { 
      document.getElementById('tambahJuzModal').classList.remove('hidden'); 
    }
    function closeTambahJuzModal() { 
      document.getElementById('tambahJuzModal').classList.add('hidden'); 
    }

    // Tutup modal jika klik di luar
    document.addEventListener('click', function(e) {
      const modal = document.getElementById('tambahJuzModal');
      if (modal && e.target === modal) {
        closeTambahJuzModal();
      }
    });
  </script>
@endsection
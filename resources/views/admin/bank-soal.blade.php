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
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
      transition: all 0.2s ease;
    }
    .juz-box:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
  </style>
@endsection

@section('content')
  <div class="space-y-6">

    <!-- Daftar Juz -->
    <div class="bg-white rounded-2xl border border-slate-200/80 custom-card p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Juz</h2>
        <button onclick="openTambahJuzModal()" class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors shadow-sm">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Tambah Juz
        </button>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($daftarJuz as $juz)
          <a href="{{ route('admin.soal.showJuz', $juz->id) }}" 
             class="juz-box bg-white border border-slate-200 rounded-2xl p-6 flex items-center justify-center aspect-square cursor-pointer">
            <span class="text-slate-700 font-semibold text-lg tracking-wide">Juz {{ $juz->nomor }}</span>
          </a>
        @empty
          {{-- Fallback dummy jika tidak ada juz --}}
          @foreach([30, 1, 2, 3] as $dummy)
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center aspect-square text-center">
              <span class="text-slate-400 font-medium text-base">Juz {{ $dummy }}</span>
              <span class="text-[10px] text-slate-400 mt-1">(Belum Aktif)</span>
            </div>
          @endforeach
        @endforelse
      </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-6 flex flex-col justify-between aspect-[4/3]">
        <span class="text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalJuz }}</span>
        <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Juz</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-6 flex flex-col justify-between aspect-[4/3]">
        <span class="text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalSurat }}</span>
        <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Surat</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-6 flex flex-col justify-between aspect-[4/3]">
        <span class="text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalSoal }}</span>
        <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Soal</span>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl custom-card p-6 flex flex-col justify-between aspect-[4/3]">
        <span class="text-5xl font-bold text-slate-800 tracking-tight leading-none">{{ $soalAudio }}</span>
        <span class="text-xs font-semibold text-slate-400 tracking-wide">Soal Audio</span>
      </div>
    </div>

  </div>
@endsection

@section('modals')
  <!-- Modal Tambah Juz -->
  <div id="tambahJuzModal" 
       class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4"
       style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden p-6">
      <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Juz Baru</h3>
      <form action="{{ route('admin.soal.storeJuz') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nomor Juz</label>
          <input type="number" min="1" max="30" name="nomor_juz" placeholder="Masukkan nomor (1-30)" required
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeTambahJuzModal()" 
            class="flex-1 border border-slate-200 text-slate-600 text-xs font-medium py-2 rounded-lg hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" 
            class="flex-1 bg-[#115E59] text-white text-xs font-medium py-2 rounded-lg hover:bg-teal-800">
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

    // Tutup modal jika klik di luar (sudah ditangani di layout, tapi sebagai fallback)
    document.addEventListener('click', function(e) {
      const modal = document.getElementById('tambahJuzModal');
      if (modal && e.target === modal) {
        closeTambahJuzModal();
      }
    });
  </script>
@endsection
@extends('layouts.guru')

@section('title', 'HafalQU — Bank Soal Hafalan')

@section('breadcrumb')
  Bank Soal Hafalan
@endsection

@section('header_actions')
  {{-- Tidak ada tombol aksi di header untuk halaman ini --}}
@endsection

@section('styles')
  <style>
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
    .juz-menu-btn {
      transition: all 0.15s ease;
    }
    .stat-card {
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .stat-icon-wrap {
      width: 42px; height: 42px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
    }
  </style>
@endsection

@section('content')
  <div class="space-y-6">

    <!-- Daftar Juz -->
    <div class="bg-white rounded-2xl border border-slate-200/80 custom-card p-6">
      <div class="flex items-center justify-between mb-1">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Juz</h2>
        <button onclick="openTambahJuzModal()" class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors shadow-sm">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Tambah Juz
        </button>
      </div>
      <p class="text-xs text-slate-400 mb-6">Kelola juz, arahkan ke daftar surat, atau ubah/hapus juz yang sudah dibuat.</p>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($daftarJuz as $juz)
          <div class="relative group">
            <a href="{{ route('guru.soal.showJuz', $juz->id) }}"
               class="juz-box bg-gradient-to-br from-white to-teal-50/40 border border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center gap-1.5 aspect-square cursor-pointer">
              <div class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center mb-1">
                <svg class="w-4.5 h-4.5 text-teal-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
              </div>
              <span class="text-slate-700 font-semibold text-lg tracking-wide leading-none">Juz {{ $juz->nomor }}</span>
              @if(isset($juz->surats_count))
                <span class="text-[11px] text-slate-400 font-medium">{{ $juz->surats_count }} Surat</span>
              @endif
            </a>

            {{-- Aksi Edit / Hapus — muncul saat kartu di-hover, ditempatkan di luar <a> supaya klik tidak ikut menavigasi --}}
            <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button type="button"
                      class="juz-menu-btn w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-teal-700 hover:border-teal-300 shadow-sm"
                      title="Edit Juz"
                      data-update-url="{{ route('guru.soal.updateJuz', $juz->id) }}"
                      data-nomor="{{ $juz->nomor }}"
                      onclick="openEditJuzModal(this)">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </button>
              <button type="button"
                      class="juz-menu-btn w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-300 shadow-sm"
                      title="Hapus Juz"
                      data-delete-url="{{ route('guru.soal.destroyJuz', $juz->id) }}"
                      data-nomor="{{ $juz->nomor }}"
                      onclick="openHapusJuzModal(this)">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6"/><path d="M14 11v6"/>
                  <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </button>
            </div>
          </div>
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
      <div class="stat-card bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between aspect-[4/3]">
        <div class="stat-icon-wrap bg-teal-50">
          <svg class="w-5 h-5 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
          </svg>
        </div>
        <div>
          <span class="text-4xl font-bold text-slate-800 tracking-tight leading-none block">{{ $totalJuz }}</span>
          <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Juz</span>
        </div>
      </div>

      <div class="stat-card bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between aspect-[4/3]">
        <div class="stat-icon-wrap bg-indigo-50">
          <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16v16H4z"/><path d="M4 9h16"/><path d="M9 4v16"/>
          </svg>
        </div>
        <div>
          <span class="text-4xl font-bold text-slate-800 tracking-tight leading-none block">{{ $totalSurat }}</span>
          <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Surat</span>
        </div>
      </div>

      <div class="stat-card bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between aspect-[4/3]">
        <div class="stat-icon-wrap bg-amber-50">
          <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <span class="text-4xl font-bold text-slate-800 tracking-tight leading-none block">{{ $totalSoal }}</span>
          <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Soal</span>
        </div>
      </div>

      <div class="stat-card bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between aspect-[4/3]">
        <div class="stat-icon-wrap bg-rose-50">
          <svg class="w-5 h-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>
          </svg>
        </div>
        <div>
          <span class="text-4xl font-bold text-slate-800 tracking-tight leading-none block">{{ $soalAudio }}</span>
          <span class="text-xs font-semibold text-slate-400 tracking-wide">Soal Audio</span>
        </div>
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
      <form action="{{ route('guru.soal.storeJuz') }}" method="POST" class="space-y-4">
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

  <!-- Modal Edit Juz -->
  <div id="editJuzModal"
       class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4"
       style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden p-6">
      <h3 class="text-sm font-bold text-slate-800 mb-4">Edit Juz</h3>
      <form id="editJuzForm" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nomor Juz</label>
          <input type="number" min="1" max="30" name="nomor_juz" id="editJuzNomor" required
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeEditJuzModal()"
            class="flex-1 border border-slate-200 text-slate-600 text-xs font-medium py-2 rounded-lg hover:bg-slate-50">
            Batal
          </button>
          <button type="submit"
            class="flex-1 bg-[#115E59] text-white text-xs font-medium py-2 rounded-lg hover:bg-teal-800">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus Juz -->
  <div id="hapusJuzModal"
       class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4"
       style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden p-6 text-center">
      <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
      <h3 class="text-sm font-bold text-slate-800">Hapus <span id="hapusJuzLabel">Juz</span>?</h3>
      <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
        Semua surat dan soal di dalam juz ini akan ikut terhapus. Tindakan ini tidak bisa dibatalkan.
      </p>
      <form id="hapusJuzForm" method="POST" class="mt-5 flex gap-2">
        @csrf
        @method('DELETE')
        <button type="button" onclick="closeHapusJuzModal()"
          class="flex-1 border border-slate-200 text-slate-600 text-xs font-medium py-2 rounded-lg hover:bg-slate-50">
          Batal
        </button>
        <button type="submit"
          class="flex-1 bg-red-600 text-white text-xs font-medium py-2 rounded-lg hover:bg-red-700">
          Ya, Hapus
        </button>
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

    function openEditJuzModal(btn) {
      document.getElementById('editJuzForm').action = btn.dataset.updateUrl;
      document.getElementById('editJuzNomor').value = btn.dataset.nomor;
      document.getElementById('editJuzModal').classList.remove('hidden');
    }
    function closeEditJuzModal() {
      document.getElementById('editJuzModal').classList.add('hidden');
    }

    function openHapusJuzModal(btn) {
      document.getElementById('hapusJuzForm').action = btn.dataset.deleteUrl;
      document.getElementById('hapusJuzLabel').textContent = 'Juz ' + btn.dataset.nomor;
      document.getElementById('hapusJuzModal').classList.remove('hidden');
    }
    function closeHapusJuzModal() {
      document.getElementById('hapusJuzModal').classList.add('hidden');
    }

    // Tutup modal jika klik di luar (sudah ditangani di layout, tapi sebagai fallback)
    document.addEventListener('click', function(e) {
      ['tambahJuzModal', 'editJuzModal', 'hapusJuzModal'].forEach(function(id) {
        const modal = document.getElementById(id);
        if (modal && e.target === modal) {
          modal.classList.add('hidden');
        }
      });
    });
  </script>
@endsection
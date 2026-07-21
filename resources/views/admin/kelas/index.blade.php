@extends('layouts.admin')

@section('title', 'Data Kelas — HafalQU Admin')
@section('page_title', 'Data Kelas')
@section('page_subtitle', 'Tambah dan kelola kelas yang tersedia')

@section('header_actions')
  <button onclick="openTambahModal()"
    class="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Tambah Kelas
  </button>
@endsection

@section('content')
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-4">Semua Kelas</p>

  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50">
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide w-16">No</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Kelas</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Deskripsi</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Siswa</th>
          <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide w-32">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($kelas as $i => $k)
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $i + 1 }}</td>
          <td class="px-5 py-3.5">
            <span class="font-medium text-slate-800">{{ $k->nama }}</span>
          </td>
          <td class="px-5 py-3.5 text-slate-500 text-sm">{{ $k->deskripsi ?: '—' }}</td>
          <td class="px-5 py-3.5">
            <a href="{{ route('admin.siswa.showByKelas', $k->id) }}"
               class="text-xs font-medium text-teal-600 hover:text-teal-700 hover:underline flex items-center gap-1">
              Lihat Siswa
              <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </td>
          <td class="px-5 py-3.5 text-center">
            <div class="flex items-center justify-center gap-2">
              <button type="button"
                onclick="openEditModal({{ json_encode($k) }})"
                class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 p-1.5 rounded-md transition-colors" title="Edit Kelas">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </button>
              <button type="button"
                onclick="confirmDelete({{ $k->id }}, '{{ $k->nama }}')"
                class="text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors" title="Hapus Kelas">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                  <line x1="10" y1="11" x2="10" y2="17"/>
                  <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-12 text-slate-400 text-sm">
            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Belum ada kelas
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@section('modals')
  <!-- Modal Tambah Kelas -->
  <div id="tambahModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Tambah Kelas Baru</h2>
          <p class="text-xs text-slate-400 mt-0.5">Buat kelas baru untuk semester ini</p>
        </div>
        <button onclick="closeTambahModal()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.kelas.store') }}" class="px-6 py-5 space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Kelas</label>
          <input type="text" name="nama" placeholder="cth. Kelas 1A"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat tentang kelas ini..."
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 resize-none"></textarea>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeTambahModal()"
            class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit Kelas -->
  <div id="editModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Edit Data Kelas</h2>
          <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi kelas yang dipilih</p>
        </div>
        <button onclick="closeEditModal()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
      <form id="editForm" method="POST" class="px-6 py-5 space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Kelas</label>
          <input type="text" name="nama" id="edit_nama" placeholder="cth. Kelas 1A"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <textarea name="deskripsi" id="edit_deskripsi" rows="3" placeholder="Keterangan singkat tentang kelas ini..."
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 resize-none"></textarea>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeEditModal()"
            class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Perbarui
          </button>
        </div>
      </form>
    </div>
  </div>

  <form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
  </form>
@endsection

@section('scripts')
  <script>
    // Logika Modal Tambah
    function openTambahModal()  { document.getElementById('tambahModal').classList.remove('hidden'); }
    function closeTambahModal() { document.getElementById('tambahModal').classList.add('hidden'); }

    // Logika Modal Edit
    function openEditModal(kelasObj) {
      const modal = document.getElementById('editModal');
      const form = document.getElementById('editForm');

      document.getElementById('edit_nama').value = kelasObj.nama;
      document.getElementById('edit_deskripsi').value = kelasObj.deskripsi || '';

      form.action = `/admin/kelas/${kelasObj.id}`;
      modal.classList.remove('hidden');
    }

    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    // Logika Hapus Data
    function confirmDelete(id, namaKelas) {
      if(confirm(`Apakah Anda yakin ingin menghapus "${namaKelas}"? Menghapus kelas mungkin akan berdampak pada relasi data siswa.`)) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/kelas/${id}`;
        deleteForm.submit();
      }
    }
  </script>
@endsection
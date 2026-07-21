@extends('layouts.admin')

@section('title', 'Akun Guru — HafalQU Admin')
@section('page_title', 'Akun Guru')
@section('page_subtitle', 'Kelola data dan akun guru pengajar')

@section('header_actions')
  <button onclick="openTambahModal()"
    class="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Tambah Guru
  </button>
@endsection

@section('content')
  @if(session('success'))
    <div class="flex items-center gap-3 bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-lg mb-6 text-sm">
      <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-4">Daftar Guru</p>

  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50">
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide w-16">No</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Username</th>
          <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kelas Diampu</th>
          <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide w-32">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($gurus as $i => $guru)
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $i + 1 }}</td>
          <td class="px-5 py-3.5">
            <div class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                {{ strtoupper(substr($guru->name, 0, 1)) }}
              </div>
              <span class="font-medium text-slate-800">{{ $guru->name }}</span>
            </div>
          </td>
          <td class="px-5 py-3.5 text-slate-500">{{ $guru->username }}</td>
          <td class="px-5 py-3.5">
            <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full">
              {{ $guru->kelas->nama ?? '—' }}
            </span>
          </td>
          <td class="px-5 py-3.5 text-center">
            <div class="flex items-center justify-center gap-2">
              <button type="button" 
                onclick="openEditModal({{ json_encode($guru) }})"
                class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 p-1.5 rounded-md transition-colors" title="Edit">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </button>
              <button type="button" 
                onclick="confirmDelete({{ $guru->id }}, '{{ $guru->name }}')"
                class="text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors" title="Hapus">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center py-12 text-slate-400 text-sm">
            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Belum ada data guru
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@section('modals')
  <!-- Modal Tambah -->
  <div id="tambahModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Tambah Akun Guru</h2>
          <p class="text-xs text-slate-400 mt-0.5">Isi data berikut untuk mendaftarkan guru baru</p>
        </div>
        <button onclick="closeTambahModal()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form action="{{ route('admin.guru.store') }}" method="POST" class="px-6 py-5 space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Lengkap</label>
          <input type="text" name="name" placeholder="Masukkan nama guru"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">username</label>
          <input type="text" name="username" placeholder="nama@sekolah.id"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Password</label>
          <input type="password" name="password" placeholder="Min. 8 karakter"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Kelas Diampu</label>
          <select name="kelas_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 bg-white" required>
            <option value="">— Pilih Kelas —</option>
            @foreach($kelas as $k)
              <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
          </select>
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

  <!-- Modal Edit -->
  <div id="editModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Edit Akun Guru</h2>
          <p class="text-xs text-slate-400 mt-0.5">Ubah data akun guru yang terpilih</p>
        </div>
        <button onclick="closeEditModal()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="editForm" method="POST" class="px-6 py-5 space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Lengkap</label>
          <input type="text" name="name" id="edit_name" placeholder="Masukkan nama guru"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Username</label>
          <input type="" name="username" id="edit_username" placeholder="nama@sekolah.id"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Password Baru <span class="text-slate-400">(Opsional)</span></label>
          <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400">
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Kelas Diampu</label>
          <select name="kelas_id" id="edit_kelas_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 bg-white" required>
            <option value="">— Pilih Kelas —</option>
            @foreach($kelas as $k)
              <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
          </select>
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
    function openEditModal(guru) {
      const modal = document.getElementById('editModal');
      const form = document.getElementById('editForm');
      
      document.getElementById('edit_name').value = guru.name;
      document.getElementById('edit_username').value = guru.username;
      document.getElementById('edit_kelas_id').value = guru.kelas_id || '';
      
      form.action = `/admin/guru/${guru.id}`;
      
      modal.classList.remove('hidden');
    }
    
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    // Logika Konfirmasi Hapus
    function confirmDelete(id, name) {
      if(confirm(`Apakah Anda yakin ingin menghapus data guru "${name}"?`)) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/guru/${id}`;
        deleteForm.submit();
      }
    }

    // Penutup modal otomatis jika klik di luar area modal
    window.addEventListener('click', function(e) {
      const tambahModal = document.getElementById('tambahModal');
      const editModal = document.getElementById('editModal');
      if (e.target === tambahModal) closeTambahModal();
      if (e.target === editModal) closeEditModal();
    });
  </script>
@endsection
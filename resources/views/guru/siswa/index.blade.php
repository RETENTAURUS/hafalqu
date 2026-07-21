@extends('layouts.guru')

@section('title', 'Manajemen Siswa — HafalQU Guru')
@section('breadcrumb')
  <span class="text-teal-700">Manajemen Siswa</span>
@endsection

@section('header_actions')
  <button onclick="openTambahModal()"
    class="flex items-center gap-2 bg-[#115E59] hover:bg-teal-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Tambah Siswa
  </button>
@endsection

@section('content')
  @if(!$kelas)
    <div class="text-center py-20 text-slate-400">
      <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
      </svg>
      <p class="text-sm font-medium">Anda belum memiliki kelas yang diampu.</p>
      <p class="text-xs mt-1">Hubungi administrator untuk menambahkan kelas.</p>
    </div>
  @else
    <div class="mb-4">
      <p class="text-sm text-slate-600">
        Kelas: <span class="font-semibold text-slate-800">{{ $kelas->nama }}</span>
        <span class="mx-2 text-slate-300">|</span>
        <span class="text-slate-500">Total Siswa: <span class="font-semibold text-slate-800">{{ $siswas->count() }}</span></span>
      </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide w-16">No</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kelas</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide w-32">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($siswas as $index => $siswa)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $index + 1 }}</td>
            <td class="px-5 py-3.5">
              <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-semibold flex-shrink-0">
                  {{ strtoupper(substr($siswa->name, 0, 1)) }}
                </div>
                <span class="font-medium text-slate-800">{{ $siswa->name }}</span>
              </div>
            </td>
            <td class="px-5 py-3.5 text-slate-500">{{ $siswa->email }}</td>
            <td class="px-5 py-3.5">
              <span class="text-xs font-medium bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full">
                {{ $siswa->kelas->nama ?? '—' }}
              </span>
            </td>
            <td class="px-5 py-3.5 text-center">
              <div class="flex items-center justify-center gap-2">
                <button
                  onclick="openEditModal('{{ $siswa->id }}', '{{ $siswa->name }}', '{{ $siswa->email }}')"
                  class="text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 p-1.5 rounded-md transition-colors" title="Edit">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <form action="{{ route('guru.siswa.destroy', $siswa->id) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus siswa ini?')" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors" title="Hapus">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                      <line x1="10" y1="11" x2="10" y2="17"/>
                      <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-12 text-slate-400 text-sm">
              <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              Belum ada siswa di kelas ini
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @endif
@endsection

@section('modals')
  <!-- MODAL TAMBAH SISWA -->
  <div id="tambahModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Tambah Siswa</h2>
          <p class="text-xs text-slate-400 mt-0.5">Daftarkan siswa baru ke kelas {{ $kelas->nama ?? 'Anda' }}</p>
        </div>
        <button onclick="closeTambahModal()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form action="{{ route('guru.siswa.store') }}" method="POST" class="px-6 py-5 space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Nama Lengkap</label>
          <input type="text" name="name" placeholder="Nama siswa"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Alamat Email</label>
          <input type="email" name="email" placeholder="siswa@email.com"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Password</label>
          <input type="password" name="password" placeholder="Min. 8 karakter"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400" required>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeTambahModal()"
            class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 bg-[#115E59] hover:bg-teal-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDIT SISWA -->
  <div id="editModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4" style="backdrop-filter:blur(2px)">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <div>
          <h2 class="text-base font-semibold text-slate-800">Edit Siswa</h2>
          <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi akun siswa</p>
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
          <input type="text" id="edit_name" name="name"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Alamat Email</label>
          <input type="email" id="edit_email" name="email"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800" required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1.5">Password <span class="text-slate-400 font-normal">(Opsional)</span></label>
          <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
            class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400">
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" onclick="closeEditModal()"
            class="flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Batal
          </button>
          <button type="submit"
            class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            Perbarui
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function openTambahModal()  { document.getElementById('tambahModal').classList.remove('hidden'); }
    function closeTambahModal() { document.getElementById('tambahModal').classList.add('hidden'); }

    function openEditModal(id, name, email) {
      document.getElementById('edit_name').value = name;
      document.getElementById('edit_email').value = email;
      document.getElementById('editForm').action = "{{ url('guru/siswa') }}/" + id;
      document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

    // Tutup modal saat klik backdrop
    ['tambahModal','editModal'].forEach(function(id) {
      document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
      });
    });
  </script>
@endsection
@extends('layouts.guru')

@section('title', 'Manajemen Siswa — HafalQU Guru')

@section('breadcrumb')
  <span class="text-teal-700 font-semibold">Manajemen Siswa</span>
@endsection

@section('header_actions')
  <button onclick="openTambahModal()"
    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold px-4 py-2.5 sm:py-2 rounded-xl transition-colors shadow-sm">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    <span>Tambah Siswa</span>
  </button>
@endsection

@section('content')
  @if(!$kelas)
    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-12 text-center text-slate-400 shadow-sm my-4">
      <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
        <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
        </svg>
      </div>
      <p class="text-xs sm:text-sm font-bold text-slate-700">Anda belum memiliki kelas yang diampu.</p>
      <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Hubungi administrator untuk menambahkan kelas.</p>
    </div>
  @else
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
      <p class="text-xs sm:text-sm text-slate-600">
        Kelas: <span class="font-bold text-slate-800">{{ $kelas->nama }}</span>
        <span class="mx-2 text-slate-300">|</span>
        <span class="text-slate-500">Total Siswa: <span class="font-bold text-slate-800">{{ $siswas->count() }}</span></span>
      </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
      
      {{-- MOBILE CARD VIEW (Tampil Khusus Layar HP) --}}
      <div class="block md:hidden divide-y divide-slate-100">
        @forelse($siswas as $index => $siswa)
          <div class="p-3.5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr($siswa->name, 0, 1)) }}
              </div>
              <div class="min-w-0">
                <h4 class="font-bold text-slate-800 text-xs truncate">{{ $siswa->name }}</h4>
                <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $siswa->email }}</p>
                <div class="mt-1">
                  <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full inline-block">
                    {{ $siswa->kelas->nama ?? '—' }}
                  </span>
                </div>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-1.5 flex-shrink-0">
              <button
                onclick="openEditModal('{{ $siswa->id }}', '{{ $siswa->name }}', '{{ $siswa->email }}')"
                class="p-2 bg-amber-50 text-amber-700 hover:bg-amber-100 active:bg-amber-200 rounded-xl transition-colors" title="Edit">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </button>
              
              <form action="{{ route('guru.siswa.destroy', $siswa->id) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus siswa ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-xl transition-colors" title="Hapus">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                  </svg>
                </button>
              </form>
            </div>
          </div>
        @empty
          <div class="p-8 text-center text-slate-400">
            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            <p class="text-xs font-semibold">Belum ada siswa di kelas ini</p>
          </div>
        @endforelse
      </div>

      {{-- DESKTOP TABLE VIEW (Tampil Khusus Tablet/Desktop) --}}
      <table class="hidden md:table w-full text-left border-collapse text-xs sm:text-sm">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50/80 text-slate-500 font-semibold uppercase tracking-wider">
            <th class="py-3 px-4 w-12 text-center">No</th>
            <th class="py-3 px-4">Nama Siswa</th>
            <th class="py-3 px-4">Email</th>
            <th class="py-3 px-4">Kelas</th>
            <th class="py-3 px-4 text-center w-32">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @forelse($siswas as $index => $siswa)
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="py-3.5 px-4 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ strtoupper(substr($siswa->name, 0, 1)) }}
                </div>
                <span class="font-bold text-slate-800">{{ $siswa->name }}</span>
              </div>
            </td>
            <td class="py-3.5 px-4 text-slate-500 font-medium">{{ $siswa->email }}</td>
            <td class="py-3.5 px-4">
              <span class="text-xs font-semibold bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full">
                {{ $siswa->kelas->nama ?? '—' }}
              </span>
            </td>
            <td class="py-3.5 px-4 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button
                  onclick="openEditModal('{{ $siswa->id }}', '{{ $siswa->name }}', '{{ $siswa->email }}')"
                  class="p-2 bg-amber-50 text-amber-700 hover:bg-amber-100 active:bg-amber-200 rounded-xl transition-colors" title="Edit">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </button>
                <form action="{{ route('guru.siswa.destroy', $siswa->id) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus siswa ini?')" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-xl transition-colors" title="Hapus">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="py-12 text-center text-slate-400">
              <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <p class="text-xs sm:text-sm font-semibold">Belum ada siswa di kelas ini</p>
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
  <div id="tambahModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 backdrop-blur-sm">
    <div class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
        <div>
          <h2 class="text-sm sm:text-base font-bold text-slate-800">Tambah Siswa</h2>
          <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Daftarkan siswa baru ke kelas {{ $kelas->nama ?? 'Anda' }}</p>
        </div>
        <button onclick="closeTambahModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form action="{{ route('guru.siswa.store') }}" method="POST" class="px-5 sm:px-6 py-4 sm:py-5 space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
          <input type="text" name="name" placeholder="Nama siswa"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
          <input type="email" name="email" placeholder="siswa@email.com"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
          <input type="password" name="password" placeholder="Min. 8 karakter"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
        </div>
        <div class="flex flex-col-reverse sm:flex-row gap-2 pt-2">
          <button type="button" onclick="closeTambahModal()"
            class="w-full sm:flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold py-2.5 rounded-xl transition-colors">
            Batal
          </button>
          <button type="submit"
            class="w-full sm:flex-1 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDIT SISWA -->
  <div id="editModal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 backdrop-blur-sm">
    <div class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
        <div>
          <h2 class="text-sm sm:text-base font-bold text-slate-800">Edit Siswa</h2>
          <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Perbarui informasi akun siswa</p>
        </div>
        <button onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form id="editForm" method="POST" class="px-5 sm:px-6 py-4 sm:py-5 space-y-4">
        @csrf
        @method('PUT')
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
          <input type="text" id="edit_name" name="name"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
          <input type="email" id="edit_email" name="email"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password <span class="text-slate-400 font-normal">(Opsional)</span></label>
          <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        </div>
        <div class="flex flex-col-reverse sm:flex-row gap-2 pt-2">
          <button type="button" onclick="closeEditModal()"
            class="w-full sm:flex-1 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold py-2.5 rounded-xl transition-colors">
            Batal
          </button>
          <button type="submit"
            class="w-full sm:flex-1 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
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
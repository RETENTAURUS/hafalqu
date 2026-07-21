@extends('layouts.admin')

@section('title', 'HafalQU — Detail Juz ' . $juz->nomor)

@section('breadcrumb')
  <a href="{{ route('guru.soal.index') }}" class="hover:text-teal-700 transition-colors">Bank Soal Hafalan</a>
  <span class="text-slate-400">/</span>
  <span class="text-teal-700">Juz {{ $juz->nomor }}</span>
@endsection

@section('header_actions')
  @if($suratAktif)
    <button onclick="openPilihJenisSoalModal()"
      class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors shadow-sm">
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
       Soal
    </button>
  @endif
@endsection

@section('styles')
<style>
  .jenis-card { border:2px solid transparent; transition:all 0.2s ease; cursor:pointer; }
  .jenis-card:hover { border-color:#cbd5e1; background:#f8fafc; }
  .jenis-card.selected { border-color:#C9A84C; background:#fefce8; box-shadow:0 0 0 1px #C9A84C; }
  .jenis-card .icon-wrapper { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  input[type="radio"] { accent-color:#115E59; }
</style>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6 h-full">

  {{-- KIRI: Daftar Surah --}}
  <div class="col-span-12 md:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col overflow-hidden shadow-sm">
    <div class="flex items-center justify-between mb-6 flex-shrink-0">
      <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Juz {{ $juz->nomor }}</h2>
      <button onclick="openTambahSurahModal()"
        class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors shadow-sm">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
         Surah
      </button>
    </div>
    <div class="flex-1 overflow-y-auto pr-1 space-y-3">
      @forelse($juz->surats as $itemSurat)
        @php $isAktif = $suratAktif && $suratAktif->id == $itemSurat->id; @endphp
        <a href="{{ route('guru.soal.showJuz', ['juz_id' => $juz->id, 'surat_id' => $itemSurat->id]) }}"
           class="flex items-center p-4 rounded-2xl border transition-all duration-200 {{ $isAktif ? 'bg-teal-50 border-teal-300 shadow-sm' : 'bg-white border-slate-200 hover:border-slate-300 hover:bg-slate-50/60' }}">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mr-3 {{ $isAktif ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600' }}">
            <span class="text-sm font-bold">{{ $itemSurat->nomor_surat }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="font-bold text-slate-800 text-sm truncate">{{ $itemSurat->nama_surat }}</h4>
            @if(!empty($itemSurat->arti_nama))
              <p class="text-xs text-slate-400 truncate">{{ $itemSurat->arti_nama }}</p>
            @endif
            <p class="text-xs text-slate-400 font-medium mt-0.5">
              {{ $itemSurat->total_ayat }} ayat
              <span class="mx-1">·</span>
              {{ $itemSurat->soals_count ?? $itemSurat->soals()->count() }} soal
            </p>
          </div>
          @if($isAktif)
            <svg class="w-4 h-4 text-teal-600 flex-shrink-0 ml-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          @endif
        </a>
      @empty
        <div class="text-center py-12 text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-xl">
          <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          Belum ada surah di Juz ini.<br>
          <span class="text-xs">Klik <strong> Surah</strong> untuk menambahkan.</span>
        </div>
      @endforelse
    </div>
  </div>

  {{-- KANAN: Tabel Soal --}}
  <div class="col-span-12 md:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col overflow-hidden shadow-sm">
    @if($suratAktif)
      <div class="flex items-center justify-between mb-6 flex-shrink-0">
        <div>
          <h2 class="text-lg font-bold text-slate-800 tracking-tight">{{ $suratAktif->nama_surat }}</h2>
          <p class="text-xs text-slate-400 mt-0.5">
            Surah ke-{{ $suratAktif->nomor_surat }}
            @if(!empty($suratAktif->nama_arab))
              <span class="mx-1">·</span>
              <span class="font-arabic text-sm text-teal-700">{{ $suratAktif->nama_arab }}</span>
            @endif
          </p>
        </div>
        <button onclick="openPilihJenisSoalModal()"
          class="flex items-center gap-1.5 bg-[#115E59] hover:bg-teal-800 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors shadow-sm">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
           Soal
        </button>
      </div>

      <div class="flex-1 overflow-auto rounded-xl border border-slate-100">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 text-slate-600 text-xs font-semibold tracking-wide sticky top-0 z-10 border-b border-slate-100">
              <th class="py-3 px-4 w-10 text-center">No</th>
              <th class="py-3 px-4">Pertanyaan</th>
              <th class="py-3 px-4 w-24 text-center">Kesulitan</th>
              <th class="py-3 px-4 w-16 text-center">Poin</th>
              <th class="py-3 px-4 w-28 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
            @forelse($suratAktif->soals as $index => $soal)
              <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="py-4 px-4 text-center text-slate-500 font-medium text-xs">{{ $index  + 1 }}</td>
                <td class="py-4 px-4">
                  <div class="font-medium text-slate-800 text-sm leading-snug line-clamp-2">{{ $soal->pertanyaan }}</div>
                  @if($soal->jenis === 'audio')
                    <span class="inline-flex items-center gap-1 text-[11px] text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md mt-1 font-medium">
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19V6l12-3v13M9 10l12-3"/></svg>
                      Audio
                    </span>
                  @endif
                </td>
                <td class="py-4 px-4 text-center">
                  @php
                    $kesulitan = $soal->kesulitan ?? 'Mudah';
                    $badgeClass = match($kesulitan) {
                      'Sulit'  => 'bg-red-50 text-red-600',
                      'Sedang' => 'bg-amber-50 text-amber-600',
                      default  => 'bg-emerald-50 text-emerald-700',
                    };
                  @endphp
                  <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-md {{ $badgeClass }}">
                    {{ $kesulitan }}
                  </span>
                </td>
                <td class="py-4 px-4 text-center font-bold text-slate-800 text-sm">{{ $soal->poin ?? 100 }}</td>
                <td class="py-4 px-4">
                  <div class="flex items-center justify-center gap-1.5">
                    {{-- Tombol Detail --}}
                    <button type="button" title="Detail"
                      onclick="bukaModalDetail({{ $soal->id }})"
                      class="p-1.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </button>
                    {{-- Tombol Edit --}}
                    <button type="button" title="Edit"
                      onclick="bukaModalEdit({{ $soal->id }})"
                      class="p-1.5 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                      </svg>
                    </button>
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('guru.soal.destroySoal', $soal->id) }}" method="POST"
                      onsubmit="return confirm('Hapus soal ini dari bank soal?')">
                      @csrf @method('DELETE')
                      <button type="submit" title="Hapus"
                        class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-14 text-center">
                  <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
                  <p class="text-slate-400 text-sm font-medium">Belum ada butir soal.</p>
                  <p class="text-slate-400 text-xs mt-0.5">Klik <strong>+ Soal</strong> untuk menambahkan.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    @else
      <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-slate-100 rounded-2xl bg-slate-50/50">
        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
        <h4 class="font-bold text-slate-700 text-base">Silakan Pilih Surah</h4>
        <p class="text-xs text-slate-400 max-w-xs mt-1">Klik salah satu daftar surah di panel sebelah kiri untuk memunculkan tabel bank soal hafalan.</p>
      </div>
    @endif
  </div>

</div>
@endsection

@section('modals')

{{-- ══ MODAL TAMBAH SURAH ══════════════════════════════ --}}
<div id="tambahSurahModal"
     class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 modal-backdrop"
     style="backdrop-filter:blur(2px)">
  <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm font-bold text-slate-800">Tambah Surah ke Juz {{ $juz->nomor }}</h3>
      <button onclick="closeTambahSurahModal()"
        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form action="{{ route('guru.soal.storeSurat', $juz->id) }}" method="POST">
      @csrf
      <div class="px-6 py-5 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Surah <span class="text-red-500">*</span></label>
            <input type="text" name="nama_surat" placeholder="cth. Al-Fatihah" value="{{ old('nama_surat') }}" required
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Arab</label>
            <input type="text" name="nama_arab" placeholder="cth. الفatحة" value="{{ old('nama_arab') }}" dir="rtl"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Surah <span class="text-red-500">*</span></label>
            <input type="number" name="nomor_surat" min="1" max="114" placeholder="1 — 114" value="{{ old('nomor_surat') }}" required
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Ayat <span class="text-red-500">*</span></label>
            <input type="number" name="total_ayat" min="1" placeholder="cth. 7" value="{{ old('total_ayat') }}" required
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Arti Nama Surah</label>
          <input type="text" name="arti_nama" placeholder="cth. Pembuka" value="{{ old('arti_nama') }}"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
        </div>
      </div>
      <div class="px-6 pb-5 flex justify-end gap-2 border-t border-slate-100 pt-4">
        <button type="button" onclick="closeTambahSurahModal()" class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-50">Batal</button>
        <button type="submit" class="bg-[#115E59] text-white text-xs font-semibold px-5 py-2 rounded-lg hover:bg-teal-800 shadow-sm">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ══ MODAL PILIH JENIS SOAL ══════════════════════════ --}}
<div id="pilihJenisSoalModal"
     class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 modal-backdrop"
     style="backdrop-filter:blur(2px)">
  <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm font-bold text-slate-800">Pilih Jenis Soal</h3>
      <button onclick="closePilihJenisSoalModal()"
        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="jenis-card rounded-xl p-4 flex items-start gap-3 border border-slate-200" data-type="melanjutkan" onclick="pilihJenis(this)">
        <div class="icon-wrapper bg-indigo-50 text-indigo-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h10M4 18h6" stroke-linecap="round"/></svg></div>
        <div><h4 class="text-sm font-bold text-slate-800">Melanjutkan Ayat</h4><p class="text-xs text-slate-500 mt-0.5">Siswa melanjutkan potongan ayat yang ditampilkan hingga selesai</p></div>
      </div>
      <div class="jenis-card rounded-xl p-4 flex items-start gap-3 border border-slate-200" data-type="mengisi" onclick="pilihJenis(this)">
        <div class="icon-wrapper bg-emerald-50 text-emerald-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" stroke-linecap="round"/></svg></div>
        <div><h4 class="text-sm font-bold text-slate-800">Mengisi Ayat Kosong</h4><p class="text-xs text-slate-500 mt-0.5">Ayat ditampilkan dengan kata yang dikosongkan, siswa memilih jawaban yang benar</p></div>
      </div>
      <div class="jenis-card rounded-xl p-4 flex items-start gap-3 border border-slate-200" data-type="pengetahuan" onclick="pilihJenis(this)">
        <div class="icon-wrapper bg-amber-50 text-amber-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"/></svg></div>
        <div><h4 class="text-sm font-bold text-slate-800">Soal Pengetahuan</h4><p class="text-xs text-slate-500 mt-0.5">Pertanyaan seputar pengetahuan tentang surah</p></div>
      </div>
      <div class="jenis-card rounded-xl p-4 flex items-start gap-3 border border-slate-200" data-type="audio" onclick="pilihJenis(this)">
        <div class="icon-wrapper bg-rose-50 text-rose-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" stroke-linecap="round"/></svg></div>
        <div><h4 class="text-sm font-bold text-slate-800">Soal Audio</h4><p class="text-xs text-slate-500 mt-0.5">Siswa mendengarkan audio tilawah lalu menjawab pertanyaan terkait ayat yang dibacakan</p></div>
      </div>
    </div>
    <div class="px-6 pb-5 flex justify-end gap-2 border-t border-slate-100 pt-4">
      <button onclick="closePilihJenisSoalModal()" class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-50">Batal</button>
      <button id="lanjutBuatSoalBtn" disabled onclick="lanjutBuatSoal()" class="bg-[#115E59] text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-teal-800">Lanjut Buat Soal</button>
    </div>
  </div>
</div>

{{-- ══ MODAL TAMBAH SOAL ═══════════════════════════════ --}}
@if($suratAktif)
<div id="tambahSoalModal"
     class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 modal-backdrop"
     style="backdrop-filter:blur(2px)">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h3 class="text-sm font-bold text-slate-800" id="tambahSoalTitle">Tambah Soal Baru</h3>
        <p class="text-xs text-slate-400 mt-0.5">{{ $suratAktif->nama_surat }}</p>
      </div>
      <button onclick="closeTambahSoalModal()" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form action="{{ route('guru.soal.storeSoal') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="surat_id" value="{{ $suratAktif->id }}">
      <input type="hidden" name="jenis" id="jenisSoalHidden" value="pengetahuan">
      <div class="px-6 py-5 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1.5" id="labelPertanyaan">Pertanyaan <span class="text-red-500">*</span></label>
          <textarea name="pertanyaan" id="inputPertanyaan" rows="3" required placeholder="Tuliskan pertanyaan di sini..."
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 resize-none focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600"></textarea>
        </div>
        <div class="space-y-2">
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilihan Jawaban <span class="text-red-500">*</span></label>
          @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $lower => $upper)
          <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-slate-500 w-5">{{ $upper }}.</span>
            <input type="text" name="opsi_{{ $lower }}" placeholder="Opsi {{ $upper }}" required
              class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
            <label class="flex items-center gap-1 cursor-pointer text-xs text-slate-500">
              <input type="radio" name="jawaban_benar" value="{{ $upper }}" {{ $lower === 'a' ? 'required' : '' }} class="w-4 h-4"> Benar
            </label>
          </div>
          @endforeach
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kesulitan</label>
            <select name="kesulitan" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
              <option value="Mudah">Mudah</option>
              <option value="Sedang">Sedang</option>
              <option value="Sulit">Sulit</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Poin</label>
            <input type="number" name="poin" value="100" min="1"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
        </div>
        <div id="audioUploadSection" class="hidden">
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">File Audio <span class="text-red-500">*</span></label>
          <input type="file" name="file_audio" id="inputFileAudio" accept=".mp3,.wav,.ogg"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
          <p class="text-[11px] text-slate-400 mt-1">Format: MP3, WAV, OGG. Maks. 5MB.</p>
        </div>
      </div>
      <div class="px-6 pb-5 flex justify-end gap-2 border-t border-slate-100 pt-4">
        <button type="button" onclick="closeTambahSoalModal()" class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-50">Batal</button>
        <button type="submit" class="bg-[#115E59] text-white text-xs font-semibold px-5 py-2 rounded-lg hover:bg-teal-800 shadow-sm">Simpan Soal</button>
      </div>
    </form>
  </div>
</div>
@endif

{{-- ══ MODAL DETAIL SOAL ═══════════════════════════════ --}}
<div id="modalDetail"
     class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 modal-backdrop"
     style="backdrop-filter:blur(2px)">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h3 class="text-sm font-bold text-slate-800">Detail Soal</h3>
        <p id="detail-surat" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <button onclick="document.getElementById('modalDetail').classList.add('hidden')"
        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="px-6 py-5 space-y-4">
      <div class="flex flex-wrap gap-2">
        <span id="detail-jenis"      class="text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700"></span>
        <span id="detail-kesulitan"  class="text-xs font-semibold px-2.5 py-1 rounded-md"></span>
        <span id="detail-poin"       class="text-xs font-semibold px-2.5 py-1 rounded-md bg-amber-50 text-amber-700"></span>
      </div>
      <div>
        <p class="text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Pertanyaan</p>
        <p id="detail-pertanyaan" class="text-sm text-slate-800 bg-slate-50 rounded-lg p-3 leading-relaxed"></p>
      </div>
      <div id="detail-audio-wrap" class="hidden">
        <p class="text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Audio</p>
        <audio id="detail-audio" controls class="w-full h-9 rounded-lg"></audio>
      </div>
      <div>
        <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">Pilihan Jawaban</p>
        <div class="space-y-2">
          @foreach(['A','B','C','D'] as $opt)
          <div id="detail-opsi-wrap-{{ $opt }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg border text-sm border-slate-100 bg-white">
            <span class="font-bold text-slate-500 w-5">{{ $opt }}.</span>
            <span id="detail-opsi-{{ $opt }}" class="flex-1 text-slate-700"></span>
            <svg id="detail-check-{{ $opt }}" class="w-4 h-4 text-emerald-600 hidden flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="px-6 pb-5 flex justify-end border-t border-slate-100 pt-4">
      <button onclick="document.getElementById('modalDetail').classList.add('hidden')"
        class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-50">Tutup</button>
    </div>
  </div>
</div>

{{-- ══ MODAL EDIT SOAL ═════════════════════════════════ --}}
<div id="modalEdit"
     class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 modal-backdrop"
     style="backdrop-filter:blur(2px)">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col">
    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
      <div>
        <h3 class="text-sm font-bold text-slate-800">Edit Soal</h3>
        <p id="edit-jenis-label" class="text-xs text-slate-400 mt-0.5"></p>
      </div>
      <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <!-- PERBAIKAN: Ditambahkan enctype agar bisa upload file audio saat edit -->
    <form id="form-edit" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
      @csrf @method('PUT')
      <div class="px-6 py-5 space-y-4 overflow-y-auto flex-1">
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
          <textarea name="pertanyaan" id="edit-pertanyaan" rows="3" required
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 resize-none focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600"></textarea>
        </div>
        <div class="space-y-2">
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilihan Jawaban <span class="text-red-500">*</span></label>
          @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $lower => $upper)
          <div class="flex items-center gap-2">
            <span class="text-sm font-medium text-slate-500 w-5">{{ $upper }}.</span>
            <input type="text" name="opsi_{{ $lower }}" id="edit-opsi-{{ $lower }}" required placeholder="Opsi {{ $upper }}"
              class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
            <label class="flex items-center gap-1 cursor-pointer text-xs text-slate-500">
              <input type="radio" name="jawaban_benar" id="edit-jwb-{{ $upper }}" value="{{ $upper }}" class="w-4 h-4"> Benar
            </label>
          </div>
          @endforeach
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kesulitan</label>
            <select name="kesulitan" id="edit-kesulitan"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
              <option value="Mudah">Mudah</option>
              <option value="Sedang">Sedang</option>
              <option value="Sulit">Sulit</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Poin</label>
            <input type="number" name="poin" id="edit-poin" min="1"
              class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600">
          </div>
        </div>
        <div id="edit-audio-wrap" class="hidden">
          <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ganti File Audio</label>
          <input type="file" name="file_audio" accept=".mp3,.wav,.ogg"
            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700">
          <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti audio.</p>
          <p id="edit-audio-current" class="text-[11px] text-teal-600 mt-1 hidden"></p>
        </div>
        <input type="hidden" name="jenis" id="edit-jenis">
      </div>
      <div class="px-6 pb-5 flex justify-end gap-2 border-t border-slate-100 pt-4 flex-shrink-0">
        <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
          class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-slate-50">Batal</button>
        <button type="submit"
          class="bg-[#115E59] text-white text-xs font-semibold px-5 py-2 rounded-lg hover:bg-teal-800 shadow-sm">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  const SOAL_URL = '{{ url("guru/bank-soal/soal") }}';

  // ── Modal Tambah Surah ────────────────────────
  function openTambahSurahModal()  { document.getElementById('tambahSurahModal').classList.remove('hidden'); }
  function closeTambahSurahModal() { document.getElementById('tambahSurahModal').classList.add('hidden'); }

  // ── Modal Pilih Jenis Soal ────────────────────
  let selectedJenis = null;
  function openPilihJenisSoalModal() {
    // Pengaman jika belum pilih surah tapi maksa klik tombol header (+ Soal)
    const modalTambah = document.getElementById('tambahSoalModal');
    if (!modalTambah) {
      alert('Silakan pilih salah satu surah di sebelah kiri terlebih dahulu.');
      return;
    }
    document.getElementById('pilihJenisSoalModal').classList.remove('hidden');
    document.querySelectorAll('.jenis-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('lanjutBuatSoalBtn').disabled = true;
    selectedJenis = null;
  }
  function closePilihJenisSoalModal() { document.getElementById('pilihJenisSoalModal').classList.add('hidden'); }
  function pilihJenis(el) {
    document.querySelectorAll('.jenis-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedJenis = el.dataset.type;
    document.getElementById('lanjutBuatSoalBtn').disabled = false;
  }
  function lanjutBuatSoal() {
    if (!selectedJenis) return;
    closePilihJenisSoalModal();
    bukaTambahSoalDenganJenis(selectedJenis);
  }

  // ── Modal Tambah Soal ─────────────────────────
  function bukaTambahSoalDenganJenis(jenis) {
    const modal = document.getElementById('tambahSoalModal');
    if (!modal) return;
    document.getElementById('jenisSoalHidden').value = jenis;
    const label        = document.getElementById('labelPertanyaan');
    const input        = document.getElementById('inputPertanyaan');
    const audioSection = document.getElementById('audioUploadSection');
    const audioInput   = document.getElementById('inputFileAudio');
    
    modal.querySelector('form').reset();
    
    const map = {
      melanjutkan: ['Potongan Ayat',           'Tuliskan potongan ayat yang ditampilkan...', 'Tambah Soal (Melanjutkan Ayat)'],
      mengisi:     ['Ayat dengan Kata Kosong',  'Gunakan "_____" untuk kata yang dikosongkan...', 'Tambah Soal (Mengisi Ayat Kosong)'],
      pengetahuan: ['Pertanyaan',              'Tuliskan pertanyaan seputar pengetahuan surah...', 'Tambah Soal Pengetahuan'],
      audio:       ['Pertanyaan',              'Tuliskan pertanyaan terkait audio tilawah...', 'Tambah Soal Audio'],
    };
    const [lbl, ph, title] = map[jenis] ?? ['Pertanyaan', 'Tuliskan pertanyaan...', 'Tambah Soal'];
    label.innerHTML = lbl + ' <span class="text-red-500">*</span>';
    input.placeholder = ph;
    document.getElementById('tambahSoalTitle').innerText = title;
    
    if (jenis === 'audio') {
      audioSection.classList.remove('hidden');
      if (audioInput) audioInput.required = true; // Wajib diisi saat buat baru jenis audio
    } else {
      audioSection.classList.add('hidden');
      if (audioInput) audioInput.required = false;
    }
    modal.classList.remove('hidden');
  }
  function closeTambahSoalModal() { document.getElementById('tambahSoalModal')?.classList.add('hidden'); }

  // ── Modal Detail ──────────────────────────────
  async function bukaModalDetail(id) {
    const modal = document.getElementById('modalDetail');
    modal.classList.remove('hidden');
    ['A','B','C','D'].forEach(o => {
      document.getElementById('detail-opsi-wrap-' + o).className =
        'flex items-center gap-2.5 px-3 py-2 rounded-lg border text-sm border-slate-100 bg-white';
      document.getElementById('detail-check-' + o).classList.add('hidden');
    });
    document.getElementById('detail-audio-wrap').classList.add('hidden');
    try {
      const soal = await fetch(SOAL_URL + '/' + id).then(r => r.json());
      document.getElementById('detail-surat').textContent     = soal.surat ?? '';
      document.getElementById('detail-pertanyaan').textContent = soal.pertanyaan;
      document.getElementById('detail-poin').textContent       = (soal.poin ?? 100) + ' poin';
      const jenisMap = {melanjutkan:'Melanjutkan Ayat', mengisi:'Mengisi Kosong', pengetahuan:'Pengetahuan', audio:'Audio'};
      document.getElementById('detail-jenis').textContent = jenisMap[soal.jenis] ?? soal.jenis;
      
      const kdEl = document.getElementById('detail-kesulitan');
      const kesulitan = soal.kesulitan ?? 'Mudah';
      kdEl.textContent = kesulitan;
      kdEl.className = 'text-xs font-semibold px-2.5 py-1 rounded-md ' +
        ({Sulit:'bg-red-50 text-red-600', Sedang:'bg-amber-50 text-amber-600'}[kesulitan] ?? 'bg-emerald-50 text-emerald-700');
        
      ['A','B','C','D'].forEach(o => {
        document.getElementById('detail-opsi-' + o).textContent = soal['opsi_' + o.toLowerCase()] ?? '';
        if (o === soal.jawaban_benar) {
          document.getElementById('detail-opsi-wrap-' + o).className =
            'flex items-center gap-2.5 px-3 py-2 rounded-lg border text-sm border-emerald-200 bg-emerald-50';
          document.getElementById('detail-check-' + o).classList.remove('hidden');
        }
      });
      if (soal.jenis === 'audio' && soal.file_audio) {
        document.getElementById('detail-audio').src = soal.file_audio;
        document.getElementById('detail-audio-wrap').classList.remove('hidden');
      }
    } catch(e) { console.error(e); }
  }

  // ── Modal Edit ────────────────────────────────
  async function bukaModalEdit(id) {
    document.getElementById('modalEdit').classList.remove('hidden');
    try {
      const soal = await fetch(SOAL_URL + '/' + id + '/edit').then(r => r.json());
      document.getElementById('form-edit').action = SOAL_URL + '/' + soal.id;
      document.getElementById('edit-pertanyaan').value  = soal.pertanyaan;
      document.getElementById('edit-opsi-a').value      = soal.opsi_a ?? '';
      document.getElementById('edit-opsi-b').value      = soal.opsi_b ?? '';
      document.getElementById('edit-opsi-c').value      = soal.opsi_c ?? '';
      document.getElementById('edit-opsi-d').value      = soal.opsi_d ?? '';
      document.getElementById('edit-kesulitan').value   = soal.kesulitan ?? 'Mudah';
      document.getElementById('edit-poin').value        = soal.poin ?? 100;
      document.getElementById('edit-jenis').value       = soal.jenis;
      
      if (soal.jawaban_benar) {
        const jwb = document.getElementById('edit-jwb-' + soal.jawaban_benar.toUpperCase());
        if (jwb) jwb.checked = true;
      }
      
      const jenisMap = {melanjutkan:'Melanjutkan Ayat', mengisi:'Mengisi Kosong', pengetahuan:'Pengetahuan', audio:'Audio'};
      document.getElementById('edit-jenis-label').textContent = jenisMap[soal.jenis] ?? soal.jenis;
      
      const aw = document.getElementById('edit-audio-wrap');
      const ac = document.getElementById('edit-audio-current');
      if (soal.jenis === 'audio') {
        aw.classList.remove('hidden');
        if (soal.file_audio) { 
          // Ambil nama filenya saja untuk tampilan estetik
          const filename = soal.file_audio.split('/').pop();
          ac.textContent = 'Audio saat ini: ' + filename; 
          ac.classList.remove('hidden'); 
        } else { 
          ac.classList.add('hidden'); 
        }
      } else {
        aw.classList.add('hidden');
      }
    } catch(e) { console.error(e); }
  }

  // ── Tutup modal via backdrop click ──────────────────────
  document.addEventListener('click', function(e) {
    ['tambahSurahModal','pilihJenisSoalModal','tambahSoalModal','modalDetail','modalEdit'].forEach(id => {
      const el = document.getElementById(id);
      if (el && e.target === el) el.classList.add('hidden');
    });
  });
</script>
@endsection
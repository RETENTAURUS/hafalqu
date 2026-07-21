@extends('layouts.admin')

@section('title', 'Tambah Lencana — HafalQU Guru')
@section('page_title', 'Tambah Lencana Baru')
@section('page_subtitle', 'Buat lencana untuk memotivasi siswa')

@section('content')
<div class="max-w-2xl mx-auto space-y-4 sm:space-y-6">
  
  {{-- LIVE PREVIEW CARD (MOBILE FRIENDLY) --}}
  <div class="bg-gradient-to-br from-teal-600 to-teal-800 rounded-2xl p-4 sm:p-6 text-white shadow-sm flex items-center justify-between gap-4">
    <div class="space-y-1">
      <span class="text-[10px] sm:text-xs font-semibold tracking-wider uppercase bg-white/20 text-white px-2.5 py-0.5 rounded-full inline-block">
        Pratinjau Lencana
      </span>
      <h3 id="preview_name" class="text-base sm:text-lg font-bold">Nama Lencana</h3>
      <p id="preview_desc" class="text-xs text-teal-100 line-clamp-2">Deskripsi lencana akan tampil di sini...</p>
    </div>
    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl sm:text-3xl flex-shrink-0 shadow-inner" id="preview_icon">
      🏅
    </div>
  </div>

  {{-- FORM CARD --}}
  <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-sm">
    <form action="{{ route('guru.lencana.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
      @csrf

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Nama Lencana <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="input_name" value="{{ old('name') }}" required placeholder="Contoh: Hafiz Juz 30" 
          oninput="document.getElementById('preview_name').innerText = this.value || 'Nama Lencana'"
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" id="input_desc" rows="3" placeholder="Jelaskan lencana ini..." 
          oninput="document.getElementById('preview_desc').innerText = this.value || 'Deskripsi lencana akan tampil di sini...'"
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 resize-none">{{ old('description') }}</textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Ikon / Emoji</label>
          <input type="text" name="icon" id="input_icon" value="{{ old('icon', '🏅') }}" 
            oninput="document.getElementById('preview_icon').innerText = this.value || '🏅'"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Gambar Lencana</label>
          <input type="file" name="image" accept="image/*" 
            class="w-full border border-slate-200 rounded-xl px-3 py-1.5 text-xs sm:text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
          @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Level Lencana</label>
        <select name="level" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
          <option value="bronze" {{ old('level')=='bronze' ? 'selected' : '' }}>🟤 Perunggu</option>
          <option value="silver" {{ old('level')=='silver' ? 'selected' : '' }}>⚪ Perak</option>
          <option value="gold" {{ old('level')=='gold' ? 'selected' : '' }}>🟡 Emas</option>
          <option value="platinum" {{ old('level')=='platinum' ? 'selected' : '' }}>⬜ Platinum</option>
        </select>
      </div>

      <hr class="my-5 border-slate-100">
      
      <div>
        <h3 class="font-semibold text-slate-800 text-sm mb-3 flex items-center gap-2">
          <span>🎯</span> Kriteria Pencapaian
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Tipe Kriteria</label>
            <select name="criteria_type" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <option value="poin" {{ old('criteria_type')=='poin' ? 'selected' : '' }}>Poin</option>
              <option value="quiz_selesai" {{ old('criteria_type')=='quiz_selesai' ? 'selected' : '' }}>Jumlah Quiz Selesai</option>
              <option value="nilai_sempurna" {{ old('criteria_type')=='nilai_sempurna' ? 'selected' : '' }}>Nilai Sempurna (100)</option>
              <option value="hafalan" {{ old('criteria_type')=='hafalan' ? 'selected' : '' }}>Jumlah Hafalan</option>
              <option value="juz_selesai" {{ old('criteria_type')=='juz_selesai' ? 'selected' : '' }}>Juz Selesai</option>
            </select>
          </div>
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Nilai Target</label>
            <input type="number" name="criteria_value" value="{{ old('criteria_value', 1) }}" min="1" 
              class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            @error('criteria_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Quiz Spesifik</label>
            <select name="quiz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <option value="">— Tidak terikat —</option>
              @foreach($quizzes as $quiz)
                <option value="{{ $quiz->id }}" {{ old('quiz_id')==$quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Surat Spesifik</label>
            <select name="surat_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <option value="">— Tidak terikat —</option>
              @foreach($surats as $surat)
                <option value="{{ $surat->id }}" {{ old('surat_id')==$surat->id ? 'selected' : '' }}>{{ $surat->nama_surat }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2 md:col-span-1">
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Juz Spesifik</label>
            <select name="juz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <option value="">— Tidak terikat —</option>
              @foreach($juzs as $juz)
                <option value="{{ $juz->id }}" {{ old('juz_id')==$juz->id ? 'selected' : '' }}>Juz {{ $juz->nomor }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <hr class="my-5 border-slate-100">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
              class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500">
            <div>
              <span class="text-xs sm:text-sm font-medium text-slate-800 block">Lencana Aktif</span>
              <span class="text-[11px] text-slate-400 block">Siswa dapat memperoleh lencana ini</span>
            </div>
          </label>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Poin Tambahan</label>
          <input type="number" name="required_points" value="{{ old('required_points', 0) }}" min="0" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        </div>
      </div>

      {{-- ACTION BUTTONS --}}
      <div class="flex items-center gap-2.5 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.lencana.index') }}" 
          class="flex-1 sm:flex-none text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-medium rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors">
          Batal
        </a>
        <button type="submit" 
          class="flex-1 sm:flex-none px-6 py-2.5 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white text-xs sm:text-sm font-medium rounded-xl shadow-sm transition-colors active:scale-95">
          Simpan Lencana
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
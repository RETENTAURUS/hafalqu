@extends('layouts.admin')

@section('title', 'Edit Lencana — HafalQU Guru')
@section('page_title', 'Edit Lencana')
@section('page_subtitle', 'Perbarui informasi lencana')

@section('content')
<div class="max-w-2xl mx-auto space-y-4 sm:space-y-6">

  {{-- LIVE PREVIEW CARD (MOBILE FRIENDLY) --}}
  <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-4 sm:p-6 text-white shadow-sm flex items-center justify-between gap-4">
    <div class="space-y-1">
      <span class="text-[10px] sm:text-xs font-semibold tracking-wider uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2.5 py-0.5 rounded-full inline-block">
        Mode Edit
      </span>
      <h3 id="preview_name" class="text-base sm:text-lg font-bold">{{ $badge->name }}</h3>
      <p id="preview_desc" class="text-xs text-slate-300 line-clamp-2">{{ $badge->description ?: 'Tidak ada deskripsi' }}</p>
    </div>
    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center justify-center text-2xl sm:text-3xl flex-shrink-0 shadow-inner overflow-hidden" id="preview_container">
      @if($badge->image)
        <img id="preview_img" src="{{ asset('storage/badges/'.$badge->image) }}" class="w-full h-full object-cover">
      @else
        <span id="preview_icon">{{ $badge->icon ?? '🏅' }}</span>
      @endif
    </div>
  </div>

  {{-- FORM CARD --}}
  <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 p-4 sm:p-6 shadow-sm">
    <form action="{{ route('guru.lencana.update', $badge->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Nama Lencana <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="input_name" value="{{ old('name', $badge->name) }}" required 
          oninput="document.getElementById('preview_name').innerText = this.value || 'Nama Lencana'"
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" id="input_desc" rows="3" 
          oninput="document.getElementById('preview_desc').innerText = this.value || 'Tidak ada deskripsi'"
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 resize-none">{{ old('description', $badge->description) }}</textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Ikon / Emoji</label>
          <input type="text" name="icon" value="{{ old('icon', $badge->icon ?? '🏅') }}" 
            oninput="const iconSpan = document.getElementById('preview_icon'); if(iconSpan) iconSpan.innerText = this.value || '🏅'"
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
        </div>

        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Gambar Lencana</label>
          @if($badge->image)
            <div class="mb-2 flex items-center gap-2">
              <img src="{{ asset('storage/badges/'.$badge->image) }}" class="w-8 h-8 object-cover rounded-lg border border-slate-200">
              <span class="text-[11px] text-slate-400">Gambar saat ini</span>
            </div>
          @endif
          <input type="file" name="image" accept="image/*" 
            class="w-full border border-slate-200 rounded-xl px-3 py-1.5 text-xs sm:text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
          @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Level Lencana</label>
        <select name="level" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
          <option value="bronze" {{ old('level', $badge->level)=='bronze' ? 'selected' : '' }}>🟤 Perunggu</option>
          <option value="silver" {{ old('level', $badge->level)=='silver' ? 'selected' : '' }}>⚪ Perak</option>
          <option value="gold" {{ old('level', $badge->level)=='gold' ? 'selected' : '' }}>🟡 Emas</option>
          <option value="platinum" {{ old('level', $badge->level)=='platinum' ? 'selected' : '' }}>⬜ Platinum</option>
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
            <select name="criteria_type" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
              <option value="poin" {{ old('criteria_type', $badge->criteria_type)=='poin' ? 'selected' : '' }}>Poin</option>
              <option value="quiz_selesai" {{ old('criteria_type', $badge->criteria_type)=='quiz_selesai' ? 'selected' : '' }}>Jumlah Quiz Selesai</option>
              <option value="nilai_sempurna" {{ old('criteria_type', $badge->criteria_type)=='nilai_sempurna' ? 'selected' : '' }}>Nilai Sempurna (100)</option>
              <option value="hafalan" {{ old('criteria_type', $badge->criteria_type)=='hafalan' ? 'selected' : '' }}>Jumlah Hafalan</option>
              <option value="juz_selesai" {{ old('criteria_type', $badge->criteria_type)=='juz_selesai' ? 'selected' : '' }}>Juz Selesai</option>
            </select>
          </div>
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Nilai Target</label>
            <input type="number" name="criteria_value" value="{{ old('criteria_value', $badge->criteria_value) }}" min="1" 
              class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
            @error('criteria_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Quiz Spesifik</label>
            <select name="quiz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
              <option value="">— Tidak terikat —</option>
              @foreach($quizzes as $quiz)
                <option value="{{ $quiz->id }}" {{ old('quiz_id', $badge->quiz_id)==$quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Surat Spesifik</label>
            <select name="surat_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
              <option value="">— Tidak terikat —</option>
              @foreach($surats as $surat)
                <option value="{{ $surat->id }}" {{ old('surat_id', $badge->surat_id)==$surat->id ? 'selected' : '' }}>{{ $surat->nama_surat }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2 md:col-span-1">
            <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Juz Spesifik</label>
            <select name="juz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
              <option value="">— Tidak terikat —</option>
              @foreach($juzs as $juz)
                <option value="{{ $juz->id }}" {{ old('juz_id', $badge->juz_id)==$juz->id ? 'selected' : '' }}>Juz {{ $juz->nomor }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <hr class="my-5 border-slate-100">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3">
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $badge->is_active) ? 'checked' : '' }} 
              class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
            <div>
              <span class="text-xs sm:text-sm font-medium text-slate-800 block">Lencana Aktif</span>
              <span class="text-[11px] text-slate-400 block">Siswa dapat memperoleh lencana ini</span>
            </div>
          </label>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Poin Tambahan</label>
          <input type="number" name="required_points" value="{{ old('required_points', $badge->required_points ?? 0) }}" min="0" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
        </div>
      </div>

      {{-- ACTION BUTTONS --}}
      <div class="flex items-center gap-2.5 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.lencana.index') }}" 
          class="flex-1 sm:flex-none text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-medium rounded-xl hover:bg-slate-50 active:bg-slate-100 transition-colors">
          Batal
        </a>
        <button type="submit" 
          class="flex-1 sm:flex-none px-6 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-xs sm:text-sm font-medium rounded-xl shadow-sm transition-colors active:scale-95">
          Perbarui Lencana
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
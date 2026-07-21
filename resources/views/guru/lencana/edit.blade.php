@extends('layouts.guru')

@section('title', 'Edit Lencana — HafalQU Guru')
@section('page_title', 'Edit Lencana')
@section('page_subtitle', 'Perbarui informasi lencana')

@section('content')
<div class="max-w-2xl mx-auto px-0 sm:px-4">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
    <form action="{{ route('guru.lencana.update', $badge->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      @method('PUT')

      {{-- Nama Lencana --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
          Nama Lencana <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $badge->name) }}" required 
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
        @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" rows="3" 
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">{{ old('description', $badge->description) }}</textarea>
      </div>

      {{-- Ikon & Gambar --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Ikon / Emoji</label>
          <input type="text" name="icon" value="{{ old('icon', $badge->icon ?? '🏅') }}" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Gambar Lencana</label>
          @if($badge->image)
            <div class="mb-2 flex items-center gap-2">
              <img src="{{ asset('storage/badges/'.$badge->image) }}" class="w-10 h-10 object-cover rounded-xl border border-slate-200">
              <span class="text-[11px] text-slate-400">Gambar saat ini</span>
            </div>
          @endif
          <input type="file" name="image" accept="image/*" 
            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
          @error('image') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Level Lencana --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Level Lencana</label>
        <select name="level" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
          <option value="bronze" {{ old('level', $badge->level)=='bronze' ? 'selected' : '' }}>🟤 Perunggu</option>
          <option value="silver" {{ old('level', $badge->level)=='silver' ? 'selected' : '' }}>⚪ Perak</option>
          <option value="gold" {{ old('level', $badge->level)=='gold' ? 'selected' : '' }}>🟡 Emas</option>
          <option value="platinum" {{ old('level', $badge->level)=='platinum' ? 'selected' : '' }}>⬜ Platinum</option>
        </select>
      </div>

      <hr class="my-6 border-slate-100">

      {{-- Header Kriteria --}}
      <div class="flex items-center gap-2 mb-2">
        <span class="text-base sm:text-lg">🎯</span>
        <h3 class="font-bold text-slate-800 text-xs sm:text-sm">Kriteria Pencapaian</h3>
      </div>

      {{-- Tipe & Nilai Target --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Tipe Kriteria</label>
          <select name="criteria_type" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
            <option value="poin" {{ old('criteria_type', $badge->criteria_type)=='poin' ? 'selected' : '' }}>Poin</option>
            <option value="quiz_selesai" {{ old('criteria_type', $badge->criteria_type)=='quiz_selesai' ? 'selected' : '' }}>Jumlah Quiz Selesai</option>
            <option value="nilai_sempurna" {{ old('criteria_type', $badge->criteria_type)=='nilai_sempurna' ? 'selected' : '' }}>Nilai Sempurna (100)</option>
            <option value="hafalan" {{ old('criteria_type', $badge->criteria_type)=='hafalan' ? 'selected' : '' }}>Jumlah Hafalan</option>
            <option value="juz_selesai" {{ old('criteria_type', $badge->criteria_type)=='juz_selesai' ? 'selected' : '' }}>Juz Selesai</option>
            <option value="login_streak" {{ old('criteria_type', $badge->criteria_type)=='login_streak' ? 'selected' : '' }}>Login Berturut-turut (Hari)</option>
            <option value="leaderboard_rank" {{ old('criteria_type', $badge->criteria_type)=='leaderboard_rank' ? 'selected' : '' }}>Peringkat Leaderboard</option>
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Nilai Target</label>
          <input type="number" name="criteria_value" value="{{ old('criteria_value', $badge->criteria_value) }}" min="1" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
          @error('criteria_value') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Keterikatan Spesifik --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Quiz Spesifik</label>
          <select name="quiz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
            <option value="">— Tidak terikat —</option>
            @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}" {{ old('quiz_id', $badge->quiz_id)==$quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Surat Spesifik</label>
          <select name="surat_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
            <option value="">— Tidak terikat —</option>
            @foreach($surats as $surat)
              <option value="{{ $surat->id }}" {{ old('surat_id', $badge->surat_id)==$surat->id ? 'selected' : '' }}>{{ $surat->nama_surat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Juz Spesifik</label>
          <select name="juz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
            <option value="">— Tidak terikat —</option>
            @foreach($juzs as $juz)
              <option value="{{ $juz->id }}" {{ old('juz_id', $badge->juz_id)==$juz->id ? 'selected' : '' }}>Juz {{ $juz->nomor }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <hr class="my-6 border-slate-100">

      {{-- Pengaturan Tambahan --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $badge->is_active) ? 'checked' : '' }} 
              class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
            <span class="text-xs sm:text-sm font-semibold text-slate-700">Lencana Aktif</span>
          </label>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Poin Bonus</label>
          <input type="number" name="required_points" value="{{ old('required_points', $badge->required_points ?? 0) }}" min="0" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.lencana.index') }}" 
          class="w-full sm:w-auto text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
          Batal
        </a>
        <button type="submit" 
          class="w-full sm:w-auto px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Perbarui Lencana
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
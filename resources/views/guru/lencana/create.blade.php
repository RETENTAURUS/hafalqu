@extends('layouts.guru')

@section('title', 'Tambah Lencana — HafalQU Guru')
@section('page_title', 'Tambah Lencana Baru')
@section('page_subtitle', 'Buat lencana untuk memotivasi siswa')

@section('content')
<div class="max-w-2xl mx-auto px-0 sm:px-4">
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
    <form action="{{ route('guru.lencana.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf

      {{-- Nama Lencana --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
          Nama Lencana <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Hafiz Juz 30" 
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
        @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" rows="3" placeholder="Jelaskan cara mendapatkan lencana ini..." 
          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">{{ old('description') }}</textarea>
      </div>

      {{-- Ikon & Gambar --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Ikon / Emoji</label>
          <input type="text" name="icon" value="{{ old('icon', '🏅') }}" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Gambar Lencana</label>
          <input type="file" name="image" accept="image/*" 
            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
          @error('image') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Level Lencana --}}
      <div>
        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Level Lencana</label>
        <select name="level" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
          <option value="bronze" {{ old('level')=='bronze' ? 'selected' : '' }}>🟤 Perunggu</option>
          <option value="silver" {{ old('level')=='silver' ? 'selected' : '' }}>⚪ Perak</option>
          <option value="gold" {{ old('level')=='gold' ? 'selected' : '' }}>🟡 Emas</option>
          <option value="platinum" {{ old('level')=='platinum' ? 'selected' : '' }}>⬜ Platinum</option>
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
          <select name="criteria_type" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            <option value="poin" {{ old('criteria_type')=='poin' ? 'selected' : '' }}>Poin</option>
            <option value="quiz_selesai" {{ old('criteria_type')=='quiz_selesai' ? 'selected' : '' }}>Jumlah Quiz Selesai</option>
            <option value="nilai_sempurna" {{ old('criteria_type')=='nilai_sempurna' ? 'selected' : '' }}>Nilai Sempurna (100)</option>
            <option value="hafalan" {{ old('criteria_type')=='hafalan' ? 'selected' : '' }}>Jumlah Hafalan</option>
            <option value="juz_selesai" {{ old('criteria_type')=='juz_selesai' ? 'selected' : '' }}>Juz Selesai</option>
            <option value="login_streak" {{ old('criteria_type')=='login_streak' ? 'selected' : '' }}>Login Berturut-turut (Hari)</option>
            <option value="leaderboard_rank" {{ old('criteria_type')=='leaderboard_rank' ? 'selected' : '' }}>Peringkat Leaderboard</option>
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Nilai Target</label>
          <input type="number" name="criteria_value" value="{{ old('criteria_value', 1) }}" min="1" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
          @error('criteria_value') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Keterikatan Spesifik --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Quiz Spesifik</label>
          <select name="quiz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            <option value="">— Tidak terikat —</option>
            @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}" {{ old('quiz_id')==$quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Surat Spesifik</label>
          <select name="surat_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            <option value="">— Tidak terikat —</option>
            @foreach($surats as $surat)
              <option value="{{ $surat->id }}" {{ old('surat_id')==$surat->id ? 'selected' : '' }}>{{ $surat->nama_surat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Juz Spesifik</label>
          <select name="juz_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            <option value="">— Tidak terikat —</option>
            @foreach($juzs as $juz)
              <option value="{{ $juz->id }}" {{ old('juz_id')==$juz->id ? 'selected' : '' }}>Juz {{ $juz->nomor }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <hr class="my-6 border-slate-100">

      {{-- Pengaturan Tambahan --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
              class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500">
            <span class="text-xs sm:text-sm font-semibold text-slate-700">Lencana Aktif</span>
          </label>
        </div>
        <div>
          <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Poin Bonus</label>
          <input type="number" name="required_points" value="{{ old('required_points', 0) }}" min="0" 
            class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.lencana.index') }}" 
          class="w-full sm:w-auto text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
          Batal
        </a>
        <button type="submit" 
          class="w-full sm:w-auto px-5 py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Simpan Lencana
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
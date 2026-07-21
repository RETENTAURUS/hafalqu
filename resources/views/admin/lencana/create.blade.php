@extends('layouts.admin')

@section('title', 'Tambah Lencana — HafalQU Guru')
@section('page_title', 'Tambah Lencana Baru')
@section('page_subtitle', 'Buat lencana untuk memotivasi siswa')

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
    <form action="{{ route('guru.lencana.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lencana <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Hafiz Juz 30" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500">
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
        <textarea name="description" rows="3" placeholder="Jelaskan lencana ini..." class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Ikon / Emoji</label>
          <input type="text" name="icon" value="{{ old('icon', '🏅') }}" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Gambar Lencana</label>
          <input type="file" name="image" accept="image/*" class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
          @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Level Lencana</label>
        <select name="level" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500">
          <option value="bronze" {{ old('level')=='bronze' ? 'selected' : '' }}>🟤 Perunggu</option>
          <option value="silver" {{ old('level')=='silver' ? 'selected' : '' }}>⚪ Perak</option>
          <option value="gold" {{ old('level')=='gold' ? 'selected' : '' }}>🟡 Emas</option>
          <option value="platinum" {{ old('level')=='platinum' ? 'selected' : '' }}>⬜ Platinum</option>
        </select>
      </div>

      <hr class="my-6 border-slate-200">
      <h3 class="font-semibold text-slate-800 text-sm mb-4">🎯 Kriteria Pencapaian</h3>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Kriteria</label>
          <select name="criteria_type" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500">
            <option value="poin" {{ old('criteria_type')=='poin' ? 'selected' : '' }}>Poin</option>
            <option value="quiz_selesai" {{ old('criteria_type')=='quiz_selesai' ? 'selected' : '' }}>Jumlah Quiz Selesai</option>
            <option value="nilai_sempurna" {{ old('criteria_type')=='nilai_sempurna' ? 'selected' : '' }}>Nilai Sempurna (100)</option>
            <option value="hafalan" {{ old('criteria_type')=='hafalan' ? 'selected' : '' }}>Jumlah Hafalan</option>
            <option value="juz_selesai" {{ old('criteria_type')=='juz_selesai' ? 'selected' : '' }}>Juz Selesai</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Target</label>
          <input type="number" name="criteria_value" value="{{ old('criteria_value', 1) }}" min="1" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500">
          @error('criteria_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Quiz Spesifik</label>
          <select name="quiz_id" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
            <option value="">— Tidak terikat —</option>
            @foreach($quizzes as $quiz)
              <option value="{{ $quiz->id }}" {{ old('quiz_id')==$quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Surat Spesifik</label>
          <select name="surat_id" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
            <option value="">— Tidak terikat —</option>
            @foreach($surats as $surat)
              <option value="{{ $surat->id }}" {{ old('surat_id')==$surat->id ? 'selected' : '' }}>{{ $surat->nama_surat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Juz Spesifik</label>
          <select name="juz_id" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
            <option value="">— Tidak terikat —</option>
            @foreach($juzs as $juz)
              <option value="{{ $juz->id }}" {{ old('juz_id')==$juz->id ? 'selected' : '' }}>Juz {{ $juz->nomor }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <hr class="my-6 border-slate-200">

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 rounded">
            <span class="text-sm font-medium text-slate-700">Lencana Aktif</span>
          </label>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Poin Tambahan</label>
          <input type="number" name="required_points" value="{{ old('required_points', 0) }}" min="0" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
        </div>
      </div>

      <div class="flex gap-3 pt-4 border-t border-slate-100">
        <a href="{{ route('guru.lencana.index') }}" class="px-6 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50">Batal</a>
        <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm">Simpan Lencana</button>
      </div>
    </form>
  </div>
</div>
@endsection
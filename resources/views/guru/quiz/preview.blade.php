@extends('layouts.guru')

@section('title', 'Preview Quiz — HafalQU Guru')
@section('page_title', 'Preview Quiz')
@section('page_subtitle', 'Konfirmasi dan atur detail quiz')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
  
  <div class="flex-1">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <h3 class="font-semibold text-slate-800 mb-4">Detail Quiz</h3>
      
      <form action="{{ route('guru.quiz.store') }}" method="POST">
        @csrf
        
        <div class="space-y-4">
          <!-- Judul -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Quiz</label>
            <input type="text" name="title" placeholder="cth. Quiz Ulangan Surah An-Naba'" required
                   class="w-full max-w-md border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
          </div>

          <!-- Isian Juz -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Juz Quiz</label>
            <div class="flex items-center gap-2 max-w-md">
              <input type="hidden" name="juz_id" value="{{ $juz->id ?? '' }}">
              <input type="text" value="Juz {{ $juz->nomor ?? '-' }}" disabled
                     class="w-full bg-slate-50 border border-slate-200 text-slate-500 rounded-lg px-4 py-2 text-sm cursor-not-allowed">
              <a href="{{ route('guru.quiz.pilihJuz') }}" class="text-xs text-teal-600 hover:text-teal-700 font-medium whitespace-nowrap">
                Ganti Juz
              </a>
            </div>
            <p class="text-xs text-slate-400 mt-1">Juz otomatis ditentukan berdasarkan pilihan Anda di langkah pertama.</p>
          </div>

          <!-- Tipe Pengerjaan -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Pengerjaan</label>
            <div class="grid grid-cols-2 gap-3 max-w-md">
              <label class="relative flex flex-col gap-1 border border-slate-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipe_pengerjaan" value="sekolah" checked class="absolute top-3 right-3 w-4 h-4 text-teal-600">
                <span class="text-sm font-semibold text-slate-800">🏫 Di Sekolah</span>
                <span class="text-xs text-slate-500">Dikerjakan saat jam pelajaran, diawasi guru</span>
              </label>
              <label class="relative flex flex-col gap-1 border border-slate-200 rounded-lg p-3 cursor-pointer has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 hover:bg-slate-50 transition-colors">
                <input type="radio" name="tipe_pengerjaan" value="rumah" class="absolute top-3 right-3 w-4 h-4 text-teal-600">
                <span class="text-sm font-semibold text-slate-800">🏠 Di Rumah</span>
                <span class="text-xs text-slate-500">Dikerjakan mandiri di luar jam sekolah</span>
              </label>
            </div>
          </div>

          <!-- Kelas Tujuan -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelas Tujuan</label>
            <select name="kelas_id" class="w-full max-w-md border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
              <option value="">Semua Kelas</option>
              @foreach(\App\Models\Kelas::all() as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
              @endforeach
            </select>
          </div>

          <!-- Durasi -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Durasi (menit)</label>
            <input type="number" name="duration" value="30" min="1" max="180"
                   class="w-32 border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
          </div>

          <!-- Tanggal Mulai & Selesai -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Mulai</label>
              <input type="datetime-local" name="start_date"
                     class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Selesai</label>
              <input type="datetime-local" name="end_date"
                     class="w-full border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>
          </div>

          <!-- Batas Percobaan -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Batas Percobaan</label>
            <input type="number" name="attempt_limit" value="0" min="0"
                   class="w-32 border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            <p class="text-xs text-slate-400 mt-1">0 = tak terbatas</p>
          </div>

          <!-- Ringkasan Soal -->
          <div class="space-y-2 pt-4 border-t border-slate-100">
            <p class="text-sm font-medium text-slate-700">Ringkasan Soal:</p>
            @foreach($surats as $surat)
            <div class="flex justify-between text-sm border-b border-slate-100 py-1.5">
              <span>{{ $surat->nama_surat }}</span>
              <span class="font-medium">{{ $config[$surat->id]['jumlah'] ?? 0 }} soal</span>
            </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold pt-2 border-t-2 border-teal-200">
              <span>Total</span>
              <span class="text-teal-700">{{ $totalSoal }} soal</span>
            </div>
          </div>

          <div class="flex gap-3 pt-4">
            <a href="{{ route('guru.quiz.konfigurasi') }}" 
               class="px-6 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
              Kembali ke Konfigurasi
            </a>
            <button type="submit" class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg shadow-sm">
              Buat Quiz
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Sidebar ringkasan -->
  <div class="lg:w-80 flex-shrink-0">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
      <h4 class="font-semibold text-slate-800 text-sm mb-3">Ringkasan</h4>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-slate-600">Total Soal</span>
          <span class="font-bold text-teal-700">{{ $totalSoal }}</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-slate-600">Surat</span>
          <span>{{ $surats->count() }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
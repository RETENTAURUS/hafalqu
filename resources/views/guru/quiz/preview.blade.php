@extends('layouts.guru')

@section('title', 'Preview Quiz — HafalQU Guru')
@section('page_title', 'Preview Quiz')
@section('page_subtitle', 'Konfirmasi dan atur detail quiz')

@section('content')
<div class="flex flex-col-reverse lg:flex-row gap-5 lg:gap-6 items-start">
  
  {{-- Form Detail & Konfirmasi (Kiri di Desktop) --}}
  <div class="w-full flex-1">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">
      <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-4 pb-3 border-b border-slate-100 uppercase tracking-wider">
        Detail & Pengaturan Quiz
      </h3>
      
      <form action="{{ route('guru.quiz.store') }}" method="POST">
        @csrf
        
        <div class="space-y-4 sm:space-y-5">
          
          {{-- Judul Quiz --}}
          <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Judul Quiz <span class="text-red-500">*</span></label>
            <input type="text" name="title" placeholder="cth. Quiz Ulangan Surah An-Naba'" required
                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
          </div>

          {{-- Isian Juz --}}
          <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Juz Quiz</label>
            <div class="flex items-center gap-2">
              <input type="hidden" name="juz_id" value="{{ $juz->id ?? '' }}">
              <input type="text" value="Juz {{ $juz->nomor ?? '-' }}" disabled
                     class="w-full bg-slate-50 border border-slate-200 text-slate-500 font-semibold rounded-xl px-3.5 py-2.5 text-xs sm:text-sm cursor-not-allowed">
              <a href="{{ route('guru.quiz.pilihJuz') }}" 
                 class="px-3 py-2.5 bg-teal-50 text-[#115E59] hover:bg-teal-100 rounded-xl text-xs font-bold transition-colors whitespace-nowrap flex-shrink-0">
                Ganti Juz
              </a>
            </div>
            <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Juz otomatis ditentukan berdasarkan pilihan Anda di langkah pertama.</p>
          </div>

          {{-- Tipe Pengerjaan --}}
          <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Tipe Pengerjaan</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
              <label class="relative flex flex-col gap-0.5 border border-slate-200/80 rounded-2xl p-3.5 cursor-pointer has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/60 hover:bg-slate-50 transition-all">
                <input type="radio" name="tipe_pengerjaan" value="sekolah" checked class="absolute top-3.5 right-3.5 w-4 h-4 text-teal-600 focus:ring-teal-500">
                <span class="text-xs sm:text-sm font-bold text-slate-800">🏫 Di Sekolah</span>
                <span class="text-[11px] text-slate-400 leading-normal">Dikerjakan saat jam pelajaran, diawasi guru</span>
              </label>

              <label class="relative flex flex-col gap-0.5 border border-slate-200/80 rounded-2xl p-3.5 cursor-pointer has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/60 hover:bg-slate-50 transition-all">
                <input type="radio" name="tipe_pengerjaan" value="rumah" class="absolute top-3.5 right-3.5 w-4 h-4 text-teal-600 focus:ring-teal-500">
                <span class="text-xs sm:text-sm font-bold text-slate-800">🏠 Di Rumah</span>
                <span class="text-[11px] text-slate-400 leading-normal">Dikerjakan mandiri di luar jam sekolah</span>
              </label>
            </div>
          </div>

          {{-- Kelas Tujuan & Durasi --}}
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <div class="sm:col-span-2">
              <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Kelas Tujuan</label>
              <select name="kelas_id" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                <option value="">Semua Kelas</option>
                @foreach(\App\Models\Kelas::all() as $kelas)
                  <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Durasi (menit)</label>
              <input type="number" name="duration" value="30" min="1" max="180"
                     class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>
          </div>

          {{-- Tanggal Mulai & Selesai --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
              <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Tanggal Mulai</label>
              <input type="datetime-local" name="start_date"
                     class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>
            <div>
              <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Tanggal Selesai</label>
              <input type="datetime-local" name="end_date"
                     class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
            </div>
          </div>

          {{-- Batas Percobaan --}}
          <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Batas Percobaan Pengerjaan</label>
            <div class="flex items-center gap-3">
              <input type="number" name="attempt_limit" value="0" min="0"
                     class="w-28 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <span class="text-xs text-slate-400 font-medium">(Isi 0 jika tidak ada batasan)</span>
            </div>
          </div>

          {{-- Ringkasan Distribusi Soal --}}
          <div class="space-y-2 pt-4 border-t border-slate-100">
            <p class="text-xs sm:text-sm font-bold text-slate-800">Ringkasan Distribusi Soal:</p>
            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
              @foreach($surats as $surat)
              <div class="flex justify-between items-center text-xs sm:text-sm border-b border-slate-100 py-1.5">
                <span class="font-medium text-slate-700">{{ $surat->nama_surat }}</span>
                <span class="font-bold text-teal-700">{{ $config[$surat->id]['jumlah'] ?? 0 }} soal</span>
              </div>
              @endforeach
            </div>
            <div class="flex justify-between items-center text-xs sm:text-sm font-bold pt-2 border-t-2 border-teal-500/20">
              <span class="text-slate-800">Total Soal Kuis</span>
              <span class="text-teal-700 font-black text-sm sm:text-base">{{ $totalSoal }} soal</span>
            </div>
          </div>

          {{-- Tombol Aksi --}}
          <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4 border-t border-slate-100">
            <a href="{{ route('guru.quiz.konfigurasi') }}" 
               class="w-full sm:w-auto text-center px-5 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
              Kembali ke Konfigurasi
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
              Buat Quiz
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

  {{-- Sidebar Ringkasan (Kanan di Desktop, Atas di Mobile) --}}
  <div class="w-full lg:w-80 flex-shrink-0 lg:sticky lg:top-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm space-y-3">
      <h4 class="font-bold text-slate-800 text-xs sm:text-sm uppercase tracking-wider">Ringkasan Kuis</h4>
      <div class="space-y-2.5 text-xs sm:text-sm">
        <div class="flex justify-between items-center py-1 border-b border-slate-100">
          <span class="text-slate-500 font-medium">Juz</span>
          <span class="font-bold text-slate-800">Juz {{ $juz->nomor ?? '-' }}</span>
        </div>
        <div class="flex justify-between items-center py-1 border-b border-slate-100">
          <span class="text-slate-500 font-medium">Jumlah Surat</span>
          <span class="font-bold text-slate-800">{{ $surats->count() }} Surat</span>
        </div>
        <div class="flex justify-between items-center py-1 border-b border-slate-100">
          <span class="text-slate-500 font-medium">Total Soal</span>
          <span class="font-black text-teal-700 text-sm">{{ $totalSoal }} Soal</span>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
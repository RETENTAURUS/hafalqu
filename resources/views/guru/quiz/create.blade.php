@extends('layouts.guru')

@section('title', 'Buat Quiz — Kelola Quiz')
@section('page_title', 'Buat Quiz Baru')
@section('page_subtitle', 'Pilih soal dari ' . $surat->nama_surat)

@section('content')
  <div class="mb-4">
    <a href="{{ route('guru.quiz.pilihSurat', $surat->juz_id) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Kembali ke Daftar Surat
    </a>
  </div>

  <form action="{{ route('guru.quiz.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
    @csrf
    <input type="hidden" name="surat_id" value="{{ $surat->id }}">

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Quiz</label>
      <input type="text" name="title" placeholder="cth. Quiz Surah An-Naba'" required
             class="w-full max-w-md border border-slate-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
    </div>

    <div>
      <p class="text-sm font-medium text-slate-700 mb-3">Pilih Soal (minimal 1 soal)</p>
      @if($surat->soals->isEmpty())
        <div class="text-center py-8 text-slate-400 border-2 border-dashed border-slate-200 rounded-lg">
          <p class="text-sm">Belum ada soal di surat ini.</p>
          <a href="{{ route('guru.soal.showJuz', ['juz_id' => $surat->juz_id, 'surat_id' => $surat->id]) }}"
             class="text-teal-600 text-sm hover:underline mt-2 inline-block">Tambahkan Soal</a>
        </div>
      @else
        <div class="space-y-2 max-h-96 overflow-y-auto pr-2">
          @foreach($surat->soals as $soal)
          <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
            <input type="checkbox" name="soal_ids[]" value="{{ $soal->id }}"
                   class="mt-1 w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-800">{{ $soal->pertanyaan }}</p>
              <div class="flex flex-wrap gap-2 mt-1">
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ ucfirst($soal->jenis) }}</span>
                <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">{{ $soal->kesulitan ?? 'Mudah' }}</span>
                <span class="text-xs text-slate-400">{{ $soal->poin ?? 100 }} poin</span>
              </div>
            </div>
          </label>
          @endforeach
        </div>
      @endif
    </div>

    <div class="flex gap-3 pt-4 border-t border-slate-100">
      <a href="{{ route('guru.quiz.pilihSurat', $surat->juz_id) }}"
         class="px-6 py-2 border border-slate-200 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
        Batal
      </a>
      <button type="submit"
              class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        Simpan Quiz
      </button>
    </div>
  </form>
@endsection
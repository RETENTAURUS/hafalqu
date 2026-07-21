@extends('layouts.admin')

@section('title', 'Pilih Kelas — HafalQU Admin')
@section('page_title', 'Akun Siswa')
@section('page_subtitle', 'Pilih kelas untuk melihat daftar siswa')

@section('header_actions')
  <a href="{{ route('admin.kelas.index') }}"
     class="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    Buat Kelas Baru
  </a>
@endsection

@section('content')
  <p class="text-[11px] font-semibold tracking-widest uppercase text-slate-400 mb-4">Daftar Kelas</p>

  @if($kelas->isEmpty())
    <div class="text-center py-20 text-slate-400">
      <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      <p class="text-sm">Belum ada kelas yang dibuat</p>
      <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-1.5 mt-3 text-teal-600 text-sm hover:underline">
        Buat kelas pertama
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
  @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
      @foreach($kelas as $k)
        <a href="{{ route('admin.siswa.showByKelas', $k->id) }}"
           class="kelas-card bg-white rounded-xl p-5 border border-slate-200 block transition-all duration-150 hover:border-teal-300 hover:shadow-md hover:-translate-y-0.5">
          <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center mb-3">
            <svg class="w-4 h-4 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </div>
          <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $k->nama }}</p>
          @if($k->deskripsi)
            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $k->deskripsi }}</p>
          @endif
          <p class="text-xs text-teal-600 font-medium mt-3 flex items-center gap-1">
            Lihat siswa
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </p>
        </a>
      @endforeach
    </div>
  @endif
@endsection

@section('styles')
  <style>
    .kelas-card {
      border-bottom: 2px solid transparent;
      transition: all 0.15s ease;
    }
    .kelas-card:hover {
      border-bottom-color: #0D9488;
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }
  </style>
@endsection
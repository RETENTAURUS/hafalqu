@extends('layouts.admin')

@section('title', 'Kelola Lencana — HafalQU Guru')
@section('page_title', 'Kelola Lencana')
@section('page_subtitle', 'Daftar semua lencana yang tersedia')

@section('header_actions')
  <a href="{{ route('guru.lencana.create') }}" 
    class="w-full sm:w-auto flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white text-sm font-medium px-4 py-2.5 sm:py-2 rounded-xl sm:rounded-lg transition-colors shadow-sm active:scale-95">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    <span>Tambah Lencana</span>
  </a>
@endsection

@section('content')
  @if(session('success'))
    <div class="flex items-center gap-3 bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-xl mb-4 text-xs sm:text-sm">
      <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if($badges->isEmpty())
    <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 p-8 sm:p-12 text-center text-slate-400">
      <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <circle cx="12" cy="8" r="6"/>
        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
      </svg>
      <p class="text-sm font-medium text-slate-600">Belum ada lencana.</p>
      <p class="text-xs text-slate-400 mt-0.5">Buat lencana baru untuk memotivasi pencapaian siswa.</p>
      <a href="{{ route('guru.lencana.create') }}" class="text-teal-600 font-medium text-xs sm:text-sm hover:underline mt-3 inline-flex items-center gap-1">
        <span>Buat lencana pertama</span>
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
      @foreach($badges as $badge)
      <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        <div>
          {{-- TOP BAR: ICON, NAME, STATUS & ACTIONS --}}
          <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
              <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                {{ $badge->icon ?? '🏅' }}
              </div>
              <div>
                <h4 class="font-semibold text-slate-800 text-sm sm:text-base leading-snug">{{ $badge->name }}</h4>
                <div class="flex items-center flex-wrap gap-1.5 mt-1">
                  <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full {{ $badge->level_badge['bg'] }} {{ $badge->level_badge['text'] }}">
                    {{ $badge->level_badge['label'] }}
                  </span>
                  <span class="text-[10px] sm:text-xs font-medium px-2 py-0.5 rounded-full {{ $badge->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                    {{ $badge->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </div>
              </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center gap-1 flex-shrink-0">
              <a href="{{ route('guru.lencana.edit', $badge->id) }}" 
                class="p-2 sm:p-1.5 bg-amber-50 active:bg-amber-100 text-amber-600 rounded-lg transition-colors" title="Edit">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form action="{{ route('guru.lencana.toggle', $badge->id) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" title="{{ $badge->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" 
                  class="p-2 sm:p-1.5 {{ $badge->is_active ? 'bg-yellow-50 active:bg-yellow-100 text-yellow-600' : 'bg-emerald-50 active:bg-emerald-100 text-emerald-600' }} rounded-lg transition-colors">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $badge->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' }}"/></svg>
                </button>
              </form>
              <form action="{{ route('guru.lencana.destroy', $badge->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus lencana ini?')">
                @csrf @method('DELETE')
                <button type="submit" title="Hapus" class="p-2 sm:p-1.5 bg-red-50 active:bg-red-100 text-red-600 rounded-lg transition-colors">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
              </form>
            </div>
          </div>

          {{-- DESCRIPTION --}}
          <p class="mt-3 text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">
            {{ $badge->description ?? 'Tidak ada deskripsi' }}
          </p>
        </div>

        {{-- CRITERIA CHIPS --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-1.5 text-[11px] font-medium">
          <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg">
            {{ $badge->criteria_label }}: {{ $badge->criteria_value }}
          </span>
          @if($badge->quiz) 
            <span class="bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-lg truncate max-w-[180px]">
              Quiz: {{ $badge->quiz->title }}
            </span> 
          @endif
          @if($badge->surat) 
            <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg">
              Surat: {{ $badge->surat->nama_surat }}
            </span> 
          @endif
          @if($badge->juz) 
            <span class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg">
              Juz {{ $badge->juz->nomor }}
            </span> 
          @endif
        </div>
      </div>
      @endforeach
    </div>
  @endif
@endsection
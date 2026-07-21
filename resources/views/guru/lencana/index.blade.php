@extends('layouts.guru')

@section('title', 'Kelola Lencana — HafalQU Guru')
@section('page_title', 'Kelola Lencana')
@section('page_subtitle', 'Daftar semua lencana yang tersedia')

@section('header_actions')
  <a href="{{ route('guru.lencana.create') }}" 
     class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold px-4 py-2.5 sm:py-2 rounded-xl transition-colors shadow-sm">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    <span>Tambah Lencana</span>
  </a>
@endsection

@section('content')
  @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-4 sm:mb-6 text-xs sm:text-sm flex items-center justify-between shadow-sm">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if($badges->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-12 text-center text-slate-400 shadow-sm my-4">
      <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
        <svg class="w-8 h-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="12" cy="8" r="6"/>
          <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
      </div>
      <p class="text-xs sm:text-sm font-semibold text-slate-700">Belum ada lencana.</p>
      <p class="text-[11px] sm:text-xs text-slate-400 mt-1">Buat lencana untuk memberikan motivasi ekstra kepada siswa.</p>
      <a href="{{ route('guru.lencana.create') }}" class="inline-flex items-center gap-1 text-teal-600 text-xs font-bold hover:underline mt-4">
        <span>Buat lencana pertama</span>
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  @else
    @php
      $levelBorder = [
          'bronze' => 'border-t-amber-600',
          'silver' => 'border-t-slate-400',
          'gold' => 'border-t-amber-400',
          'platinum' => 'border-t-cyan-400',
      ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-5">
      @foreach($badges as $badge)
      <div class="bg-white rounded-2xl border border-slate-200/80 border-t-4 {{ $levelBorder[$badge->level] ?? 'border-t-slate-200' }} p-4 sm:p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
        
        <div>
          {{-- Header Card: Icon, Name & Actions --}}
          <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-3">
              <x-badge-icon :badge="$badge" size="w-12 h-12 sm:w-14 sm:h-14" />
              <div>
                <h4 class="font-bold text-slate-800 text-xs sm:text-sm leading-snug">{{ $badge->name }}</h4>
                <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                  <x-level-tag :level="$badge->level" />
                  <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full {{ $badge->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $badge->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </div>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-1 flex-shrink-0">
              <a href="{{ route('guru.lencana.edit', $badge->id) }}" 
                 class="p-2 bg-amber-50 text-amber-700 hover:bg-amber-100 active:bg-amber-200 rounded-xl transition-colors" 
                 title="Edit">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>

              <form action="{{ route('guru.lencana.toggle', $badge->id) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" 
                        title="{{ $badge->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" 
                        class="p-2 {{ $badge->is_active ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} rounded-xl transition-colors">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $badge->is_active ? 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z' : 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' }}"/></svg>
                </button>
              </form>

              <form action="{{ route('guru.lencana.destroy', $badge->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus lencana ini?')">
                @csrf @method('DELETE')
                <button type="submit" title="Hapus" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-xl transition-colors">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
              </form>
            </div>
          </div>

          {{-- Description --}}
          <div class="mt-3 text-xs text-slate-500 line-clamp-2 leading-relaxed">
            {{ $badge->description ?? 'Tidak ada deskripsi' }}
          </div>
        </div>

        {{-- Tags Criteria --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-1.5 text-[10px] sm:text-xs">
          <span class="bg-slate-100 text-slate-700 font-medium px-2.5 py-0.5 rounded-md">
            {{ $badge->criteria_label }}: {{ $badge->criteria_value }}
          </span>
          @if($badge->quiz) 
            <span class="bg-indigo-50 text-indigo-700 font-medium px-2 py-0.5 rounded-md truncate max-w-[150px]">
              Quiz: {{ $badge->quiz->title }}
            </span> 
          @endif
          @if($badge->surat) 
            <span class="bg-emerald-50 text-emerald-700 font-medium px-2 py-0.5 rounded-md">
              Surat: {{ $badge->surat->nama_surat }}
            </span> 
          @endif
          @if($badge->juz) 
            <span class="bg-amber-50 text-amber-700 font-medium px-2 py-0.5 rounded-md">
              Juz: {{ $badge->juz->nomor }}
            </span> 
          @endif
        </div>

      </div>
      @endforeach
    </div>
  @endif
@endsection
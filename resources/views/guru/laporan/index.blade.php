@extends('layouts.guru')

@section('title', 'Laporan Rekap Kelas — HafalQU')

@section('breadcrumb')
  <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
  <span class="text-slate-500">Laporan</span>
  <svg class="w-3 h-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Rekap Kelas</span>
@endsection

@section('header_actions')
  <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
    <a href="{{ route('guru.laporan.export-pdf', request()->query()) }}"
       target="_blank"
       class="inline-flex items-center justify-center gap-1.5 bg-red-700 hover:bg-red-800 active:bg-red-900 text-white text-xs font-semibold px-3.5 py-2.5 sm:py-2 rounded-xl sm:rounded-lg transition-colors shadow-sm">
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      <span>Export PDF</span>
    </a>
    <a href="{{ route('guru.laporan.export-excel', request()->query()) }}"
       class="inline-flex items-center justify-center gap-1.5 bg-emerald-800 hover:bg-emerald-900 active:bg-emerald-950 text-white text-xs font-semibold px-3.5 py-2.5 sm:py-2 rounded-xl sm:rounded-lg transition-colors shadow-sm">
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
      <span>Export Excel</span>
    </a>
  </div>
@endsection

@section('content')

{{-- ═══ Filter Bar ═══ --}}
<form method="GET" action="{{ route('guru.laporan.index') }}"
      class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row flex-wrap gap-3 sm:gap-4 items-stretch md:items-end mb-4 sm:mb-6 shadow-sm">

  <div class="flex flex-col gap-1.5 flex-1 min-w-[140px]">
    <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kelas</label>
    <select name="kelas_id"
            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 sm:py-2 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
      <option value="">Semua Kelas</option>
      @foreach($kelasList as $k)
        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
      @endforeach
    </select>
  </div>

  <div class="flex flex-col gap-1.5 flex-1 min-w-[140px]">
    <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Periode</label>
    <select name="periode" id="periode-select" onchange="toggleCustomDate(this.value)"
            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 sm:py-2 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
      <option value="bulan_ini" {{ $periode=='bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
      <option value="semester"  {{ $periode=='semester'  ? 'selected' : '' }}>Semester Ini</option>
      <option value="custom"    {{ $periode=='custom'    ? 'selected' : '' }}>Pilih Tanggal</option>
    </select>
  </div>

  <div id="custom-date" class="{{ $periode=='custom' ? 'flex' : 'hidden' }} flex-col sm:flex-row gap-2.5 items-stretch sm:items-end flex-1">
    <div class="flex flex-col gap-1.5 flex-1">
      <label class="text-[11px] font-semibold text-slate-500">Dari</label>
      <input type="date" name="date_from" value="{{ $dateFrom }}"
             class="w-full border border-slate-200 rounded-xl px-3 py-2 sm:py-1.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
    </div>
    <div class="flex flex-col gap-1.5 flex-1">
      <label class="text-[11px] font-semibold text-slate-500">Sampai</label>
      <input type="date" name="date_to" value="{{ $dateTo }}"
             class="w-full border border-slate-200 rounded-xl px-3 py-2 sm:py-1.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
    </div>
  </div>

  <button type="submit"
          class="bg-[#1a3a2e] hover:bg-[#11271f] active:bg-[#0a1813] text-white font-semibold rounded-xl px-5 py-2.5 sm:py-2 text-xs sm:text-sm transition-colors shadow-sm mt-1 md:mt-0">
    Tampilkan
  </button>
</form>

{{-- ═══ Summary Cards ═══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
  @php
    $cards = [
      ['label'=>'Total Siswa',    'value'=>$summary['total_siswa'],                  'unit'=>'siswa',  'color'=>'text-[#1a3a2e]', 'bg'=>'bg-teal-50/60'],
      ['label'=>'Rata-rata Skor', 'value'=>$summary['avg_class_score'],                'unit'=>'/ 100',  'color'=>'text-amber-800', 'bg'=>'bg-amber-50/60'],
      ['label'=>'Siswa Aktif',    'value'=>$summary['persen_aktif'].'%',               'unit'=>'aktif',  'color'=>'text-emerald-800', 'bg'=>'bg-emerald-50/60'],
      ['label'=>'Tingkat Lulus',  'value'=>$summary['persen_lulus'].'%',               'unit'=>'lulus',  'color'=>'text-blue-900', 'bg'=>'bg-blue-50/60'],
    ];
  @endphp
  @foreach($cards as $card)
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col justify-between">
    <p class="text-[10px] sm:text-[11px] font-semibold text-slate-400 tracking-wider uppercase mb-1 sm:mb-2">{{ $card['label'] }}</p>
    <p class="text-2xl sm:text-3xl font-extrabold {{ $card['color'] }} leading-tight">{{ $card['value'] }}</p>
    <p class="text-[10px] sm:text-xs text-slate-400 mt-1">{{ $card['unit'] }}</p>
  </div>
  @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4 sm:mb-6">

  {{-- ═══ Panel: Distribusi Nilai ═══ --}}
  <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm flex flex-col justify-between">
    <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
      <span class="text-xs sm:text-sm font-bold text-slate-800">Distribusi Nilai</span>
      <span class="text-[11px] sm:text-xs text-slate-400">{{ $distribusi['total'] }} percobaan</span>
    </div>
    <div class="p-4 sm:p-5 space-y-3.5">
      @php
        $total = max($distribusi['total'], 1);
        $bars = [
          ['label'=>'Sempurna (100)',    'val'=>$distribusi['sempurna'],    'color'=>'bg-amber-500', 'text'=>'text-amber-700'],
          ['label'=>'Lulus',             'val'=>$distribusi['lulus'],       'color'=>'bg-emerald-600', 'text'=>'text-emerald-700'],
          ['label'=>'Tidak Lulus',       'val'=>$distribusi['tidak_lulus'], 'color'=>'bg-red-600', 'text'=>'text-red-700'],
        ];
      @endphp
      @foreach($bars as $bar)
      <div>
        <div class="flex justify-between items-center text-xs mb-1.5">
          <span class="text-slate-700 font-medium">{{ $bar['label'] }}</span>
          <span class="font-bold {{ $bar['text'] }}">{{ $bar['val'] }} <span class="font-normal text-slate-400">({{ $total > 0 ? round($bar['val']/$total*100) : 0 }}%)</span></span>
        </div>
        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
          <div class="h-full {{ $bar['color'] }} rounded-full transition-all duration-500" style="width: {{ $total > 0 ? min(round($bar['val']/$total*100), 100) : 0 }}%;"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- ═══ Panel: Aktivitas Siswa ═══ --}}
  <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
    <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100">
      <span class="text-xs sm:text-sm font-bold text-slate-800">Aktivitas Siswa</span>
    </div>
    <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
      {{-- Donut chart via SVG --}}
      @php
        $totalSiswa = max($aktivitas['total_siswa'], 1);
        $pAktif     = round($aktivitas['aktif'] / $totalSiswa * 100);
        $pBelum     = 100 - $pAktif;
        $r          = 44;
        $circ       = 2 * M_PI * $r;
        $dashAktif  = ($pAktif / 100) * $circ;
        $dashBelum  = $circ - $dashAktif;
      @endphp
      <svg width="100" height="100" viewBox="0 0 110 110" class="flex-shrink-0">
        <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#f1f5f9" stroke-width="12"/>
        <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#059669" stroke-width="12"
                stroke-dasharray="{{ $dashAktif }} {{ $dashBelum }}"
                stroke-dashoffset="{{ $circ / 4 }}"
                stroke-linecap="round"/>
        <text x="55" y="50" text-anchor="middle" class="text-lg font-extrabold fill-slate-800">{{ $pAktif }}%</text>
        <text x="55" y="66" text-anchor="middle" class="text-[11px] fill-slate-400">aktif</text>
      </svg>
      <div class="flex-1 w-full space-y-2">
        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl">
          <div class="flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-600"></div>
            <span class="text-xs text-slate-700 font-medium">Aktif Mengerjakan</span>
          </div>
          <span class="text-xs font-bold text-emerald-700">{{ $aktivitas['aktif'] }}</span>
        </div>
        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl">
          <div class="flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
            <span class="text-xs text-slate-700 font-medium">Belum Mengerjakan</span>
          </div>
          <span class="text-xs font-bold text-slate-400">{{ $aktivitas['belum'] }}</span>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ═══ Panel: Quiz Rata-rata Terendah ═══ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden mb-4 sm:mb-6 shadow-sm">
  <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100">
    <span class="text-xs sm:text-sm font-bold text-slate-800">Statistik Quiz (Diurutkan dari Nilai Terendah)</span>
  </div>
  @if($quizStats->count())

    {{-- MOBILE CARD VIEW --}}
    <div class="block md:hidden divide-y divide-slate-100">
      @foreach($quizStats as $q)
      @php
        $scoreColor = $q['avg_score'] >= 80 ? 'text-emerald-700' : ($q['avg_score'] >= 60 ? 'text-amber-700' : 'text-red-600');
        $barColor   = $q['avg_score'] >= 80 ? 'bg-emerald-600' : ($q['avg_score'] >= 60 ? 'bg-amber-500' : 'bg-red-600');
      @endphp
      <div class="p-4 space-y-2.5">
        <div class="flex justify-between items-start gap-2">
          <h4 class="font-semibold text-slate-800 text-xs sm:text-sm leading-snug">{{ $q['title'] }}</h4>
          <span class="text-xs font-bold {{ $scoreColor }} bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100 flex-shrink-0">
            Avg: {{ $q['avg_score'] }}
          </span>
        </div>
        <div class="flex justify-between text-[11px] text-slate-500 pt-1">
          <span>Percobaan: <strong>{{ $q['total_attempts'] }}</strong></span>
          <span>Passing: <strong>{{ $q['passing_score'] }}</strong></span>
          <span>Lulus: <strong class="{{ $scoreColor }}">{{ $q['persen_lulus'] }}%</strong></span>
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
          <div class="h-full {{ $barColor }} rounded-full" style="width: {{ min($q['avg_score'], 100) }}%;"></div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- DESKTOP TABLE VIEW --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs sm:text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
            <th class="py-3 px-5">Quiz</th>
            <th class="py-3 px-5 text-center">Rata-rata</th>
            <th class="py-3 px-5 text-center">Percobaan</th>
            <th class="py-3 px-5 text-center">Passing</th>
            <th class="py-3 px-5 text-center">% Lulus</th>
            <th class="py-3 px-5">Progres</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @foreach($quizStats as $q)
          @php
            $scoreColor = $q['avg_score'] >= 80 ? 'text-emerald-700' : ($q['avg_score'] >= 60 ? 'text-amber-700' : 'text-red-600');
            $barColor   = $q['avg_score'] >= 80 ? 'bg-emerald-600' : ($q['avg_score'] >= 60 ? 'bg-amber-500' : 'bg-red-600');
          @endphp
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="py-3.5 px-5 font-medium text-slate-800">{{ $q['title'] }}</td>
            <td class="py-3.5 px-5 text-center font-bold {{ $scoreColor }}">{{ $q['avg_score'] }}</td>
            <td class="py-3.5 px-5 text-center text-slate-500">{{ $q['total_attempts'] }}</td>
            <td class="py-3.5 px-5 text-center text-slate-500">{{ $q['passing_score'] }}</td>
            <td class="py-3.5 px-5 text-center font-semibold {{ $scoreColor }}">{{ $q['persen_lulus'] }}%</td>
            <td class="py-3.5 px-5 min-w-[120px]">
              <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ min($q['avg_score'], 100) }}%;"></div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="p-8 text-center text-slate-400 text-xs sm:text-sm">Belum ada data quiz untuk periode ini.</div>
  @endif
</div>

{{-- ═══ Panel: Tabel Ranking ═══ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
  <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100">
    <span class="text-xs sm:text-sm font-bold text-slate-800">Ranking Siswa</span>
  </div>
  @if($ranking->count())

    {{-- MOBILE CARD VIEW --}}
    <div class="block md:hidden divide-y divide-slate-100">
      @foreach($ranking as $r)
      <div class="p-4 flex items-center justify-between gap-3 {{ $loop->first ? 'bg-amber-50/30' : '' }}">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0">
            @if($r['rank'] == 1) 🥇 @elseif($r['rank'] == 2) 🥈 @elseif($r['rank'] == 3) 🥉 @else <span class="text-slate-400">#{{ $r['rank'] }}</span> @endif
          </div>
          <div>
            <h4 class="font-semibold text-slate-800 text-xs sm:text-sm">{{ $r['name'] }}</h4>
            <p class="text-[11px] text-slate-400 mt-0.5">
              Rata-rata: <span class="font-semibold {{ $r['avg_score'] >= 70 ? 'text-emerald-700' : 'text-red-600' }}">{{ $r['avg_score'] }}</span> · Terbaik: {{ $r['best_score'] }}
            </p>
          </div>
        </div>
        <div class="text-right flex-shrink-0">
          <span class="text-xs font-extrabold text-[#1a3a2e] block">{{ $r['points'] }} Pts</span>
          <span class="bg-emerald-50 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 rounded-full inline-block mt-0.5">
            {{ $r['quiz_dikerjakan'] }} quiz
          </span>
        </div>
      </div>
      @endforeach
    </div>

    {{-- DESKTOP TABLE VIEW --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs sm:text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
            <th class="py-3 px-5 text-center w-12">#</th>
            <th class="py-3 px-5">Nama Siswa</th>
            <th class="py-3 px-5 text-center">Total Poin</th>
            <th class="py-3 px-5 text-center">Rata-rata Skor</th>
            <th class="py-3 px-5 text-center">Skor Terbaik</th>
            <th class="py-3 px-5 text-center">Quiz Selesai</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @foreach($ranking as $r)
          <tr class="hover:bg-slate-50/50 transition-colors {{ $loop->first ? 'bg-amber-50/20' : '' }}">
            <td class="py-3.5 px-5 text-center font-bold">
              @if($r['rank'] == 1) 🥇 @elseif($r['rank'] == 2) 🥈 @elseif($r['rank'] == 3) 🥉 @else <span class="text-slate-400">#{{ $r['rank'] }}</span> @endif
            </td>
            <td class="py-3.5 px-5">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ strtoupper(substr($r['name'], 0, 1)) }}
                </div>
                <span class="font-medium text-slate-800">{{ $r['name'] }}</span>
              </div>
            </td>
            <td class="py-3.5 px-5 text-center font-bold text-[#1a3a2e]">{{ $r['points'] }}</td>
            <td class="py-3.5 px-5 text-center font-semibold {{ $r['avg_score'] >= 70 ? 'text-emerald-700' : 'text-red-600' }}">{{ $r['avg_score'] }}</td>
            <td class="py-3.5 px-5 text-center text-slate-500">{{ $r['best_score'] }}</td>
            <td class="py-3.5 px-5 text-center">
              <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                {{ $r['quiz_dikerjakan'] }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="p-8 text-center text-slate-400 text-xs sm:text-sm">Belum ada data siswa untuk periode ini.</div>
  @endif
</div>

@endsection

@section('scripts')
<script>
function toggleCustomDate(val) {
  const customDateEl = document.getElementById('custom-date');
  if (val === 'custom') {
    customDateEl.classList.remove('hidden');
    customDateEl.classList.add('flex');
  } else {
    customDateEl.classList.remove('flex');
    customDateEl.classList.add('hidden');
  }
}
</script>
@endsection
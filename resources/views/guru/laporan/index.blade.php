@extends('layouts.guru')

@section('title', 'Laporan Rekap Kelas — HafalQU')

@section('breadcrumb')
  <svg style="width:15px;height:15px;color:#6b7c74;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
  <span style="color:#6b7c74;">Laporan</span>
  <svg style="width:13px;height:13px;color:#c8d5cc;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Rekap Kelas</span>
@endsection

@section('header_actions')
  <div style="display:flex;gap:8px;">
    <a href="{{ route('guru.laporan.export-pdf', request()->query()) }}"
       target="_blank"
       style="display:inline-flex;align-items:center;gap:6px;background:#b83232;color:#fff;font-size:12px;font-weight:600;padding:8px 14px;border-radius:8px;text-decoration:none;">
      <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Export PDF
    </a>
    <a href="{{ route('guru.laporan.export-excel', request()->query()) }}"
       style="display:inline-flex;align-items:center;gap:6px;background:#1a5e3a;color:#fff;font-size:12px;font-weight:600;padding:8px 14px;border-radius:8px;text-decoration:none;">
      <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
      Export Excel
    </a>
  </div>
@endsection

@section('content')

{{-- ═══ Filter Bar ═══ --}}
<form method="GET" action="{{ route('guru.laporan.index') }}"
      style="background:#fff;border:1px solid #e8e4dc;border-radius:10px;padding:14px 16px;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;margin-bottom:20px;">

  <div style="display:flex;flex-direction:column;gap:4px;">
    <label style="font-size:11px;font-weight:600;color:#6b7c74;text-transform:uppercase;letter-spacing:0.8px;">Kelas</label>
    <select name="kelas_id"
            style="border:1px solid #ddd;border-radius:7px;padding:7px 10px;font-size:13px;color:#1e3a2a;background:#fff;min-width:140px;">
      <option value="">Semua Kelas</option>
      @foreach($kelasList as $k)
        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
      @endforeach
    </select>
  </div>

  <div style="display:flex;flex-direction:column;gap:4px;">
    <label style="font-size:11px;font-weight:600;color:#6b7c74;text-transform:uppercase;letter-spacing:0.8px;">Periode</label>
    <select name="periode" id="periode-select" onchange="toggleCustomDate(this.value)"
            style="border:1px solid #ddd;border-radius:7px;padding:7px 10px;font-size:13px;color:#1e3a2a;background:#fff;">
      <option value="bulan_ini" {{ $periode=='bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
      <option value="semester"  {{ $periode=='semester'  ? 'selected' : '' }}>Semester Ini</option>
      <option value="custom"    {{ $periode=='custom'    ? 'selected' : '' }}>Pilih Tanggal</option>
    </select>
  </div>

  <div id="custom-date" style="display:{{ $periode=='custom' ? 'flex' : 'none' }};gap:8px;align-items:flex-end;">
    <div style="display:flex;flex-direction:column;gap:4px;">
      <label style="font-size:11px;font-weight:600;color:#6b7c74;">Dari</label>
      <input type="date" name="date_from" value="{{ $dateFrom }}"
             style="border:1px solid #ddd;border-radius:7px;padding:7px 10px;font-size:13px;">
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
      <label style="font-size:11px;font-weight:600;color:#6b7c74;">Sampai</label>
      <input type="date" name="date_to" value="{{ $dateTo }}"
             style="border:1px solid #ddd;border-radius:7px;padding:7px 10px;font-size:13px;">
    </div>
  </div>

  <button type="submit"
          style="background:#1a3a2e;color:#fff;border:none;border-radius:7px;padding:8px 18px;font-size:13px;font-weight:600;cursor:pointer;">
    Tampilkan
  </button>
</form>

{{-- ═══ Summary Cards ═══ --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
  @php
    $cards = [
      ['label'=>'Total Siswa',    'value'=>$summary['total_siswa'],                    'unit'=>'siswa',  'color'=>'#1a3a2e', 'bg'=>'#e1f5f0'],
      ['label'=>'Rata-rata Skor', 'value'=>$summary['avg_class_score'],                'unit'=>'/ 100',  'color'=>'#7a5a1a', 'bg'=>'#fdf3e0'],
      ['label'=>'Siswa Aktif',    'value'=>$summary['persen_aktif'].'%',               'unit'=>'aktif',  'color'=>'#1a5e3a', 'bg'=>'#d6f0e4'],
      ['label'=>'Tingkat Lulus',  'value'=>$summary['persen_lulus'].'%',               'unit'=>'lulus',  'color'=>'#1a3a8e', 'bg'=>'#e6f1fb'],
    ];
  @endphp
  @foreach($cards as $card)
  <div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;padding:16px;">
    <p style="font-size:11px;font-weight:600;color:#6b7c74;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">{{ $card['label'] }}</p>
    <p style="font-size:28px;font-weight:800;color:{{ $card['color'] }};line-height:1;">{{ $card['value'] }}</p>
    <p style="font-size:11px;color:#a09882;margin-top:3px;">{{ $card['unit'] }}</p>
  </div>
  @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

  {{-- ═══ Panel: Distribusi Nilai ═══ --}}
  <div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;">
    <div style="padding:14px 16px 12px;border-bottom:1px solid #f0ede6;display:flex;align-items:center;justify-content:space-between;">
      <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Distribusi Nilai</span>
      <span style="font-size:11px;color:#a09882;">{{ $distribusi['total'] }} percobaan</span>
    </div>
    <div style="padding:16px;">
      @php
        $total = max($distribusi['total'], 1);
        $bars = [
          ['label'=>'Sempurna (100)',    'val'=>$distribusi['sempurna'],    'color'=>'#d4a843', 'bg'=>'#fdf3e0'],
          ['label'=>'Lulus',             'val'=>$distribusi['lulus'],       'color'=>'#2d7a5f', 'bg'=>'#e1f5f0'],
          ['label'=>'Tidak Lulus',       'val'=>$distribusi['tidak_lulus'], 'color'=>'#b83232', 'bg'=>'#fdf2f2'],
        ];
      @endphp
      @foreach($bars as $bar)
      <div style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
          <span style="font-size:12px;color:#3d4a42;">{{ $bar['label'] }}</span>
          <span style="font-size:12px;font-weight:600;color:{{ $bar['color'] }};">{{ $bar['val'] }} <span style="font-weight:400;color:#a09882;">({{ $total > 0 ? round($bar['val']/$total*100) : 0 }}%)</span></span>
        </div>
        <div style="height:8px;background:#f0ede6;border-radius:4px;overflow:hidden;">
          <div style="height:100%;background:{{ $bar['color'] }};border-radius:4px;width:{{ $total > 0 ? min(round($bar['val']/$total*100), 100) : 0 }}%;transition:width 0.6s ease;"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- ═══ Panel: Aktivitas Siswa ═══ --}}
  <div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;">
    <div style="padding:14px 16px 12px;border-bottom:1px solid #f0ede6;">
      <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Aktivitas Siswa</span>
    </div>
    <div style="padding:16px;display:flex;align-items:center;gap:20px;">
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
      <svg width="110" height="110" viewBox="0 0 110 110" style="flex-shrink:0;">
        <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#f0ede6" stroke-width="12"/>
        <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#2d7a5f" stroke-width="12"
                stroke-dasharray="{{ $dashAktif }} {{ $dashBelum }}"
                stroke-dashoffset="{{ $circ / 4 }}"
                stroke-linecap="round"/>
        <text x="55" y="50" text-anchor="middle" style="font-size:18px;font-weight:800;fill:#1e3a2a;">{{ $pAktif }}%</text>
        <text x="55" y="66" text-anchor="middle" style="font-size:11px;fill:#6b7c74;">aktif</text>
      </svg>
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:8px;padding:10px 0;border-bottom:1px solid #f5f2ec;">
          <div style="width:10px;height:10px;border-radius:50%;background:#2d7a5f;flex-shrink:0;"></div>
          <span style="font-size:13px;color:#3d4a42;flex:1;">Aktif</span>
          <span style="font-size:14px;font-weight:700;color:#2d7a5f;">{{ $aktivitas['aktif'] }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;padding:10px 0;">
          <div style="width:10px;height:10px;border-radius:50%;background:#d0cbc2;flex-shrink:0;"></div>
          <span style="font-size:13px;color:#3d4a42;flex:1;">Belum</span>
          <span style="font-size:14px;font-weight:700;color:#a09882;">{{ $aktivitas['belum'] }}</span>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ═══ Panel: Quiz Rata-rata Terendah ═══ --}}
<div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;margin-bottom:16px;">
  <div style="padding:14px 16px 12px;border-bottom:1px solid #f0ede6;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Statistik Quiz (Diurutkan dari Nilai Terendah)</span>
  </div>
  @if($quizStats->count())
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
      <thead>
        <tr style="background:#faf8f5;border-bottom:1px solid #f0ede6;">
          <th style="padding:10px 16px;text-align:left;font-weight:600;color:#6b7c74;">Quiz</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Rata-rata</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Percobaan</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Passing</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">% Lulus</th>
          <th style="padding:10px 16px;text-align:left;font-weight:600;color:#6b7c74;">Bar</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quizStats as $q)
        @php
          $scoreColor = $q['avg_score'] >= 80 ? '#2d7a5f' : ($q['avg_score'] >= 60 ? '#a06900' : '#b83232');
          $barColor   = $q['avg_score'] >= 80 ? '#2d7a5f' : ($q['avg_score'] >= 60 ? '#d4a843' : '#b83232');
        @endphp
        <tr style="border-bottom:1px solid #f5f2ec;">
          <td style="padding:10px 16px;color:#2d3a33;font-weight:500;">{{ $q['title'] }}</td>
          <td style="padding:10px 16px;text-align:center;font-weight:700;color:{{ $scoreColor }};">{{ $q['avg_score'] }}</td>
          <td style="padding:10px 16px;text-align:center;color:#6b7c74;">{{ $q['total_attempts'] }}</td>
          <td style="padding:10px 16px;text-align:center;color:#6b7c74;">{{ $q['passing_score'] }}</td>
          <td style="padding:10px 16px;text-align:center;color:{{ $scoreColor }};font-weight:600;">{{ $q['persen_lulus'] }}%</td>
          <td style="padding:10px 16px;min-width:120px;">
            <div style="height:6px;background:#f0ede6;border-radius:3px;overflow:hidden;">
              <div style="height:100%;background:{{ $barColor }};border-radius:3px;width:{{ min($q['avg_score'], 100) }}%;"></div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div style="padding:28px;text-align:center;color:#a09882;font-size:13px;">Belum ada data quiz untuk periode ini.</div>
  @endif
</div>

{{-- ═══ Panel: Tabel Ranking ═══ --}}
<div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;">
  <div style="padding:14px 16px 12px;border-bottom:1px solid #f0ede6;">
    <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Ranking Siswa</span>
  </div>
  @if($ranking->count())
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
      <thead>
        <tr style="background:#faf8f5;border-bottom:1px solid #f0ede6;">
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;width:50px;">#</th>
          <th style="padding:10px 16px;text-align:left;font-weight:600;color:#6b7c74;">Nama Siswa</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Total Poin</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Rata-rata Skor</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Skor Terbaik</th>
          <th style="padding:10px 16px;text-align:center;font-weight:600;color:#6b7c74;">Quiz Selesai</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ranking as $r)
        <tr style="border-bottom:1px solid #f5f2ec;{{ $loop->first ? 'background:#fffdf7;' : '' }}">
          <td style="padding:10px 16px;text-align:center;">
            @if($r['rank'] == 1)
              <span style="font-size:15px;">🥇</span>
            @elseif($r['rank'] == 2)
              <span style="font-size:15px;">🥈</span>
            @elseif($r['rank'] == 3)
              <span style="font-size:15px;">🥉</span>
            @else
              <span style="font-weight:600;color:#a09882;">#{{ $r['rank'] }}</span>
            @endif
          </td>
          <td style="padding:10px 16px;">
            <div style="display:flex;align-items:center;gap:9px;">
              <div style="width:28px;height:28px;border-radius:50%;background:#d4a843;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#5a3200;flex-shrink:0;">
                {{ strtoupper(substr($r['name'], 0, 1)) }}
              </div>
              <span style="font-weight:500;color:#2d3a33;">{{ $r['name'] }}</span>
            </div>
          </td>
          <td style="padding:10px 16px;text-align:center;font-weight:700;color:#1a3a2e;">{{ $r['points'] }}</td>
          <td style="padding:10px 16px;text-align:center;color:{{ $r['avg_score'] >= 70 ? '#2d7a5f' : '#b83232' }};font-weight:600;">{{ $r['avg_score'] }}</td>
          <td style="padding:10px 16px;text-align:center;color:#6b7c74;">{{ $r['best_score'] }}</td>
          <td style="padding:10px 16px;text-align:center;">
            <span style="background:#e1f5f0;color:#1a7a5e;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;">
              {{ $r['quiz_dikerjakan'] }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div style="padding:28px;text-align:center;color:#a09882;font-size:13px;">Belum ada data siswa untuk periode ini.</div>
  @endif
</div>

@endsection

@section('scripts')
<script>
function toggleCustomDate(val) {
  document.getElementById('custom-date').style.display = val === 'custom' ? 'flex' : 'none';
}
</script>
@endsection

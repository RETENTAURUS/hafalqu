<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<style>
  * { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; margin: 0; padding: 0; box-sizing: border-box; }
  body { background: #fff; color: #1e1e1e; padding: 28px 32px; }

  .header { border-bottom: 2px solid #1a3a2e; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: flex-end; }
  .header-left .title { font-size: 16px; font-weight: bold; color: #1a3a2e; }
  .header-left .sub   { font-size: 11px; color: #5a6a62; margin-top: 3px; }
  .header-right       { font-size: 10px; color: #7a8a82; text-align: right; }

  .summary-grid { display: flex; gap: 10px; margin-bottom: 18px; }
  .summary-card { flex: 1; border: 1px solid #d8d4cc; border-radius: 6px; padding: 10px 12px; }
  .summary-card .label { font-size: 9px; font-weight: bold; color: #7a8a82; text-transform: uppercase; letter-spacing: 0.7px; }
  .summary-card .value { font-size: 20px; font-weight: bold; color: #1a3a2e; margin-top: 3px; line-height: 1; }
  .summary-card .unit  { font-size: 10px; color: #9a9a92; margin-top: 2px; }

  .section-title { font-size: 11px; font-weight: bold; color: #1a3a2e; background: #f0ede6; padding: 6px 10px; border-radius: 4px; margin-bottom: 8px; margin-top: 16px; }

  table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  th { background: #1a3a2e; color: #fff; padding: 7px 10px; text-align: left; font-size: 10px; font-weight: bold; }
  th.c { text-align: center; }
  td { padding: 6px 10px; border-bottom: 1px solid #ece9e2; font-size: 10px; }
  td.c { text-align: center; }
  tr:nth-child(even) td { background: #faf8f5; }
  .rank-top td { background: #fffdf0 !important; }

  .bar-wrap { background: #ece9e2; border-radius: 3px; height: 7px; overflow: hidden; }
  .bar-fill  { height: 100%; border-radius: 3px; }

  .dist-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
  .dist-label { width: 140px; font-size: 10px; }
  .dist-count { width: 30px; text-align: right; font-weight: bold; font-size: 10px; }
  .dist-pct   { width: 40px; text-align: right; font-size: 10px; color: #7a8a82; }

  .two-col { display: flex; gap: 16px; }
  .two-col > div { flex: 1; }

  .badge { display: inline-block; border-radius: 20px; padding: 1px 8px; font-size: 9px; font-weight: bold; }
  .badge-green { background: #e1f5f0; color: #1a5e3a; }
  .badge-red   { background: #fdf2f2; color: #7a2020; }

  .footer { margin-top: 28px; border-top: 1px solid #d8d4cc; padding-top: 10px; display: flex; justify-content: space-between; font-size: 9px; color: #9a9a92; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
  <div class="header-left">
    <div class="title">Laporan Rekap Kelas — HafalQU</div>
    <div class="sub">
      Kelas: {{ $kelas->nama ?? 'Semua Kelas' }} &nbsp;|&nbsp;
      Periode: {{ \Carbon\Carbon::parse($dateFrom)->isoFormat('D MMMM YYYY') }} – {{ \Carbon\Carbon::parse($dateTo)->isoFormat('D MMMM YYYY') }}
    </div>
  </div>
  <div class="header-right">
    Dicetak: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}<br>
    Oleh: {{ $namaGuru }}
  </div>
</div>

{{-- Summary Cards --}}
<div class="summary-grid">
  <div class="summary-card">
    <div class="label">Total Siswa</div>
    <div class="value">{{ $summary['total_siswa'] }}</div>
    <div class="unit">siswa terdaftar</div>
  </div>
  <div class="summary-card">
    <div class="label">Rata-rata Skor</div>
    <div class="value">{{ $summary['avg_class_score'] }}</div>
    <div class="unit">dari 100</div>
  </div>
  <div class="summary-card">
    <div class="label">Siswa Aktif</div>
    <div class="value">{{ $summary['persen_aktif'] }}%</div>
    <div class="unit">{{ $aktivitas['aktif'] }} dari {{ $aktivitas['total_siswa'] }} siswa</div>
  </div>
  <div class="summary-card">
    <div class="label">Tingkat Lulus</div>
    <div class="value">{{ $summary['persen_lulus'] }}%</div>
    <div class="unit">dari total percobaan</div>
  </div>
</div>

{{-- Distribusi + Aktivitas side by side --}}
<div class="two-col">
  <div>
    <div class="section-title">Distribusi Nilai</div>
    @php $total = max($distribusi['total'], 1); @endphp
    @php $bars = [
      ['label'=>'Nilai Sempurna (100)', 'val'=>$distribusi['sempurna'],    'color'=>'#d4a843'],
      ['label'=>'Lulus',               'val'=>$distribusi['lulus'],       'color'=>'#2d7a5f'],
      ['label'=>'Tidak Lulus',         'val'=>$distribusi['tidak_lulus'], 'color'=>'#b83232'],
    ]; @endphp
    @foreach($bars as $b)
    <div class="dist-row">
      <div class="dist-label">{{ $b['label'] }}</div>
      <div class="dist-count">{{ $b['val'] }}</div>
      <div class="dist-pct">{{ round($b['val']/$total*100) }}%</div>
      <div style="flex:1;"><div class="bar-wrap"><div class="bar-fill" style="width:{{ min(round($b['val']/$total*100),100) }}%;background:{{ $b['color'] }};"></div></div></div>
    </div>
    @endforeach
    <div style="font-size:10px;color:#7a8a82;margin-top:4px;">Total: {{ $distribusi['total'] }} percobaan</div>
  </div>
  <div>
    <div class="section-title">Aktivitas Siswa</div>
    @php $ts = max($aktivitas['total_siswa'], 1); @endphp
    @php $acts = [
      ['label'=>'Aktif (sudah mengerjakan)', 'val'=>$aktivitas['aktif'], 'color'=>'#2d7a5f'],
      ['label'=>'Belum mengerjakan',         'val'=>$aktivitas['belum'], 'color'=>'#d0cbc2'],
    ]; @endphp
    @foreach($acts as $a)
    <div class="dist-row">
      <div class="dist-label">{{ $a['label'] }}</div>
      <div class="dist-count">{{ $a['val'] }}</div>
      <div class="dist-pct">{{ round($a['val']/$ts*100) }}%</div>
      <div style="flex:1;"><div class="bar-wrap"><div class="bar-fill" style="width:{{ min(round($a['val']/$ts*100),100) }}%;background:{{ $a['color'] }};"></div></div></div>
    </div>
    @endforeach
  </div>
</div>

{{-- Quiz Stats --}}
<div class="section-title">Statistik Quiz (Diurutkan dari Rata-rata Terendah)</div>
<table>
  <thead>
    <tr>
      <th>Judul Quiz</th>
      <th class="c">Rata-rata</th>
      <th class="c">Percobaan</th>
      <th class="c">Passing</th>
      <th class="c">% Lulus</th>
    </tr>
  </thead>
  <tbody>
    @forelse($quizStats as $q)
    @php $sc = $q['avg_score'] >= 80 ? '#2d7a5f' : ($q['avg_score'] >= 60 ? '#a06900' : '#b83232'); @endphp
    <tr>
      <td>{{ $q['title'] }}</td>
      <td class="c" style="font-weight:bold;color:{{ $sc }};">{{ $q['avg_score'] }}</td>
      <td class="c">{{ $q['total_attempts'] }}</td>
      <td class="c">{{ $q['passing_score'] }}</td>
      <td class="c"><span class="badge {{ $q['persen_lulus'] >= 70 ? 'badge-green' : 'badge-red' }}">{{ $q['persen_lulus'] }}%</span></td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center;color:#9a9a92;">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{-- Ranking --}}
<div class="section-title">Ranking Siswa</div>
<table>
  <thead>
    <tr>
      <th class="c" style="width:40px;">#</th>
      <th>Nama Siswa</th>
      <th class="c">Total Poin</th>
      <th class="c">Rata-rata Skor</th>
      <th class="c">Skor Terbaik</th>
      <th class="c">Quiz Selesai</th>
    </tr>
  </thead>
  <tbody>
    @forelse($ranking as $r)
    <tr {{ $r['rank'] <= 3 ? 'class="rank-top"' : '' }}>
      <td class="c" style="font-weight:bold;color:#{{ $r['rank']==1 ? 'd4a843' : ($r['rank']==2 ? '8a9aa2' : ($r['rank']==3 ? 'a07040' : '9a9a92')) }};">
        {{ $r['rank'] }}
      </td>
      <td style="font-weight:{{ $r['rank'] <= 3 ? 'bold' : 'normal' }};">{{ $r['name'] }}</td>
      <td class="c" style="font-weight:bold;color:#1a3a2e;">{{ $r['points'] }}</td>
      <td class="c" style="color:{{ $r['avg_score'] >= 70 ? '#2d7a5f' : '#b83232' }};font-weight:bold;">{{ $r['avg_score'] }}</td>
      <td class="c">{{ $r['best_score'] }}</td>
      <td class="c">{{ $r['quiz_dikerjakan'] }}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:#9a9a92;">Belum ada data</td></tr>
    @endforelse
  </tbody>
</table>

{{-- Footer --}}
<div class="footer">
  <span>HafalQU — Sistem Monitoring Hafalan</span>
  <span>Laporan ini digenerate otomatis oleh sistem</span>
</div>

</body>
</html>

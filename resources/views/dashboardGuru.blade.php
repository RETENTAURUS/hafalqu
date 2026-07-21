<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HafalQU - Dashboard Guru</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#1B5E3B',
              dark: '#144d30',
              light: '#2d7a52',
            },
            cream: '#F7F5F0',
            gold: '#D4A017',
          },
          fontFamily: {
            jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .sidebar-link {
      display: block;
      padding: 10px 20px;
      border-radius: 8px;
      color: #d1fae5;
      font-size: 14px;
      font-weight: 500;
      transition: background 0.2s;
      cursor: pointer;
    }
    .sidebar-link:hover { background: rgba(255,255,255,0.12); }
    .sidebar-link.active { background: rgba(255,255,255,0.18); color: #fff; font-weight: 600; }
    .stat-card {
      background: white;
      border-radius: 14px;
      padding: 20px 24px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .stat-icon {
      width: 40px; height: 40px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .podium-bar {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
    }
    .avatar-circle {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: #e8f5e9;
      border: 2px solid #1B5E3B;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
      margin-bottom: 6px;
      position: relative;
    }
    .rank-badge {
      position: absolute; bottom: -8px;
      background: #1B5E3B; color: white;
      font-size: 9px; font-weight: 700;
      border-radius: 4px; padding: 1px 4px;
    }
    .podium-block {
      width: 72px;
      border-radius: 8px 8px 0 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; font-weight: 800; color: white;
    }
    .lencana-row {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 16px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
      font-size: 14px; font-weight: 500;
      color: #2d3748;
    }
    .quick-btn {
      background: white;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px 12px;
      font-size: 13px; font-weight: 600;
      color: #1B5E3B;
      cursor: pointer;
      transition: all 0.15s;
      text-align: center;
    }
    .quick-btn:hover {
      background: #1B5E3B;
      color: white;
      border-color: #1B5E3B;
    }
    .leaderboard-row {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid #f0f0f0;
    }
    .leaderboard-row:last-child { border-bottom: none; }
  </style>
</head>
<body class="bg-cream min-h-screen flex">

  <!-- SIDEBAR -->
  <aside class="w-56 min-h-screen flex flex-col" style="background:#1B5E3B;">
    <!-- Logo -->
    <div class="px-6 pt-7 pb-6">
      <div class="text-white font-bold text-xl tracking-wide">HafalQU</div>
      <div class="text-green-300 text-xs font-medium mt-0.5 tracking-widest uppercase">Monitoring Hafalan</div>
    </div>

    <!-- User -->
    <div class="mx-4 mb-5 p-3 rounded-xl flex items-center gap-3" style="background:rgba(255,255,255,0.1)">
      <div class="w-9 h-9 rounded-full bg-green-200 flex items-center justify-center font-bold text-primary text-sm flex-shrink-0">
        {{ substr($namaGuru, 0, 1) }}
      </div>
      <div>
        <div class="text-white font-semibold text-sm">{{ $namaGuru }}</div>
        <div class="text-green-300 text-xs">{{ $peranGuru }}</div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 px-3 space-y-1">
      <a href="{{ route('guru.dashboard') }}" class="sidebar-link active">🏠&nbsp; Dashboard</a>
      <a href="#" class="sidebar-link">👥&nbsp; Manajemen Siswa</a>
      <a href="#" class="sidebar-link">📋&nbsp; Bank Soal</a>
      <a href="#" class="sidebar-link">📝&nbsp; Kelola Quiz</a>
      <a href="#" class="sidebar-link">⭐&nbsp; Sistem Poin</a>
      <a href="#" class="sidebar-link">🏅&nbsp; Kelola Lencana</a>
      <a href="#" class="sidebar-link">🏆&nbsp; Papan Peringkat</a>
      <a href="#" class="sidebar-link">📊&nbsp; Laporan</a>
    </nav>

    <!-- Keluar -->
  </aside>

  <!-- MAIN -->
  <main class="flex-1 p-8 overflow-y-auto">

    <!-- Header -->
    <div class="mb-7">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Guru</h1>
      <p class="text-gray-400 text-sm mt-1">Selamat datang, {{ $namaGuru }} — {{ $tanggal }}</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-4 gap-5 mb-7">
      <!-- Total Siswa -->
      <div class="stat-card">
        <div class="stat-icon bg-blue-50">
          <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ $totalSiswa }}</div>
        <div class="text-sm text-gray-400 font-medium">Total Siswa</div>
      </div>

      <!-- Quiz Aktif -->
      <div class="stat-card">
        <div class="stat-icon bg-green-50">
          <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ $quizAktif }}</div>
        <div class="text-sm text-gray-400 font-medium">Quiz Aktif</div>
      </div>

      <!-- Rata-rata Skor -->
      <div class="stat-card">
        <div class="stat-icon bg-purple-50">
          <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ $rataRataSkor }}%</div>
        <div class="text-sm text-gray-400 font-medium">Rata-rata Skor</div>
      </div>

      <!-- Lencana Diberikan -->
      <div class="stat-card">
        <div class="stat-icon bg-yellow-50">
          <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ $totalLencanaDiberikan }}</div>
        <div class="text-sm text-gray-400 font-medium">Lencana Diberikan</div>
      </div>
    </div>

    <!-- Bottom: Leaderboard + Aksi Cepat -->
    <div class="grid grid-cols-3 gap-6">

      <!-- Leaderboard -->
      <div class="col-span-2 bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-5">
          <div>
            <div class="font-bold text-gray-800 text-base">Leaderboard Kelas 4A</div>
            <div class="text-xs text-gray-400 mt-0.5">Top siswa berdasarkan poin</div>
          </div>
          <button class="text-xs font-semibold text-primary border border-primary/30 px-3 py-1.5 rounded-lg hover:bg-primary hover:text-white transition-all">Lihat Semua</button>
        </div>

        <!-- Podium (3 besar) -->
        <div class="flex items-end justify-center gap-4 mb-7 mt-2" style="height: 160px;">
          @php
            $top3 = $leaderboard->take(3);
          @endphp

          <!-- Rank 2 -->
          @if($top3->count() >= 2)
            <div class="podium-bar">
              <div class="text-center mb-2">
                <div class="avatar-circle" style="border-color:#718096;">
                  {{ substr($top3[1]->name, 0, 2) }}
                </div>
                <div class="text-xs font-semibold text-gray-700 mt-3">{{ $top3[1]->name }}</div>
                <div class="text-xs text-gray-400">{{ number_format($top3[1]->points) }} poin</div>
              </div>
              <div class="podium-block" style="height:90px; background:#718096;">#2</div>
            </div>
          @endif

          <!-- Rank 1 -->
          @if($top3->count() >= 1)
            <div class="podium-bar">
              <div class="text-center mb-2 relative">
                <div class="text-yellow-400 text-lg mb-1">👑</div>
                <div class="avatar-circle" style="border-color:#D4A017; width:52px; height:52px; font-size:22px;">
                  {{ substr($top3[0]->name, 0, 2) }}
                </div>
                <div class="text-xs font-semibold text-gray-800 mt-2">{{ $top3[0]->name }}</div>
                <div class="text-xs font-bold text-primary">{{ number_format($top3[0]->points) }} poin</div>
              </div>
              <div class="podium-block" style="height:120px; background:#D4A017;">#1</div>
            </div>
          @endif

          <!-- Rank 3 -->
          @if($top3->count() >= 3)
            <div class="podium-bar">
              <div class="text-center mb-2">
                <div class="avatar-circle" style="border-color:#CD7F32;">
                  {{ substr($top3[2]->name, 0, 2) }}
                </div>
                <div class="text-xs font-semibold text-gray-700 mt-3">{{ $top3[2]->name }}</div>
                <div class="text-xs text-gray-400">{{ number_format($top3[2]->points) }} poin</div>
              </div>
              <div class="podium-block" style="height:70px; background:#CD7F32;">#3</div>
            </div>
          @endif
        </div>

        <!-- List 4–6 -->
        <div class="space-y-1">
          @php
            $rank4to6 = $leaderboard->slice(3);
          @endphp
          @foreach($rank4to6 as $index => $siswa)
            <div class="leaderboard-row">
              <span class="text-sm font-bold text-gray-400 w-5">{{ $loop->iteration + 3 }}</span>
              <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-base">
                {{ substr($siswa->name, 0, 2) }}
              </div>
              <span class="flex-1 text-sm font-medium text-gray-700">{{ $siswa->name }}</span>
              <span class="text-sm font-bold text-primary">{{ number_format($siswa->points) }} poin</span>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Right Column -->
      <div class="flex flex-col gap-5">

        <!-- Aksi Cepat -->
        <div class="bg-white rounded-2xl p-5 shadow-sm">
          <div class="font-bold text-gray-800 text-sm mb-4">Aksi Cepat</div>
          <div class="grid grid-cols-2 gap-3">
            <button class="quick-btn" onclick="window.location='{}'">📝<br/>Buat Quiz</button>
            <button class="quick-btn" onclick="window.location='{}'">📋<br/>Bank Soal</button>
            <button class="quick-btn" onclick="window.location='{}'">🏅<br/>Atur Lencana</button>
            <button class="quick-btn" onclick="window.location='{}'">📥<br/>Unduh Laporan</button>
          </div>
        </div>

        <!-- Daftar Lencana Tersedia -->
        <div class="bg-white rounded-2xl p-5 shadow-sm flex-1">
          <div class="font-bold text-gray-800 text-sm mb-4">Daftar Lencana Tersedia</div>
          <div class="space-y-2">
            @foreach($lencanaList as $lencana)
              <div class="lencana-row">
                <span class="text-xl">{{ $lencana->icon ?? '🏅' }}</span>
                <span>{{ $lencana->name }}</span>
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  </main>

</body>
</html>
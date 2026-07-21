<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'HafalQU Siswa')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    * { font-family: 'Inter', sans-serif; }

    /* ── Sidebar ─────────────────────────────────── */
    .sidebar-brand-sub {
      font-size: 9px;
      letter-spacing: 1.6px;
      text-transform: uppercase;
      color: #8ab89e;
    }

    .nav-item {
      border-left: 2px solid transparent;
      transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .nav-item:hover {
      background: rgba(255,255,255,0.06);
      border-left-color: rgba(212,168,67,0.5);
      color: #d4e8dc;
    }
    .nav-active {
      background: rgba(255,255,255,0.10);
      border-left-color: #d4a843 !important;
      color: #fff !important;
    }
    .nav-active svg { color: #d4a843; }

    /* ── Content area ────────────────────────────── */
    .page-header {
      background: #fff;
      border-bottom: 1px solid #eae6de;
    }

    /* ── Flash messages ──────────────────────────── */
    .flash-success {
      background: #f0faf5;
      border: 1px solid #a7dfc4;
      color: #1a5e3a;
    }
    .flash-error {
      background: #fdf2f2;
      border: 1px solid #f5b8b8;
      color: #7a2020;
    }

    /* ── Scrollbar ───────────────────────────────── */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #c8d5cc; border-radius: 2px; }

    /* ── Focus ───────────────────────────────────── */
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #2d7a5f;
      box-shadow: 0 0 0 3px rgba(45,122,95,0.12);
    }

    /* ── Logika Responsif Mobile Tanpa Ribet JS ── */
    #sidebar-toggle { display: none; }

    @media (max-width: 768px) {
      aside {
        position: fixed;
        top: 0;
        left: -220px; /* Sembunyi di luar layar kiri */
        height: 100vh;
        z-index: 50;
        transition: left 0.3s ease-in-out;
        box-shadow: 4px 0 15px rgba(0,0,0,0.15);
      }
      
      /* Backdrop Gelap saat Sidebar Terbuka */
      .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 40;
        backdrop-filter: blur(2px);
      }

      /* Reaksi ketika checkbox dicentang */
      #sidebar-toggle:checked ~ aside {
        left: 0;
      }
      #sidebar-toggle:checked ~ .sidebar-overlay {
        display: block;
      }
      
      /* Penyesuaian jarak margin konten di HP */
      .page-header, .flash-success, .flash-error {
        margin-left: 14px !important;
        margin-right: 14px !important;
      }
      .page-header {
        padding: 14px 0 !important;
      }
      .main-content-area {
        padding: 16px 14px !important;
      }
    }

    @yield('styles')
  </style>
</head>
<body style="background:#f8f7f4; display:flex; height:100vh; overflow:hidden;">

  <input type="checkbox" id="sidebar-toggle" />
  
  <label for="sidebar-toggle" class="sidebar-overlay"></label>

  <aside style="width:200px; background:#1a3a2e; display:flex; flex-direction:column; flex-shrink:0; overflow-y:auto;">

    {{-- Gold stripe ornament --}}
    <div style="height:3px; background:repeating-linear-gradient(90deg, transparent, transparent 5px, rgba(212,168,67,0.4) 5px, rgba(212,168,67,0.4) 6px);"></div>

    {{-- Logo --}}
    <div style="padding:20px 18px 16px; border-bottom:1px solid rgba(255,255,255,0.08);">
      <div style="display:flex; align-items:center; justify-content:space-between; gap:9px;">
        <div style="display:flex; align-items:center; gap:9px;">
          <div style="width:28px; height:28px; border-radius:7px; background:rgba(255,255,255,0.08); display:flex; align-items:center; justify-content:center; flex-shrink:0; padding:4px;">
            <img src="{{ asset('img/logo.png') }}" alt="Logo HafalQu" style="width:100%; height:100%; object-fit:contain;">
          </div>
          <div>
            <div style="font-size:14px; font-weight:700; color:#fff; letter-spacing:0.2px; line-height:1;">HafalQU</div>
            <div style="font-size:8px;" class="sidebar-brand-sub">Monitoring</div>
          </div>
        </div>
        
        <label for="sidebar-toggle" class="md:hidden text-emerald-300 hover:text-white cursor-pointer p-1">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </label>
      </div>
    </div>

    {{-- User info --}}
    <div style="padding:12px 18px 14px; border-bottom:1px solid rgba(255,255,255,0.08);">
      <div style="display:flex; align-items:center; gap:9px; background:rgba(255,255,255,0.06); border-radius:8px; padding:8px 10px;">
        <div style="width:30px; height:30px; border-radius:50%; background:#d4a843; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#5a3200; flex-shrink:0;">
          {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
        </div>
        <div style="min-width:0;">
          <div style="font-size:12px; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ Auth::user()->name ?? 'Siswa' }}
          </div>
          <div style="font-size:10px; color:#8ab89e; margin-top:1px;">
            {{ Auth::user()->kelas->nama ?? 'Kelas' }}
          </div>
        </div>
      </div>
    </div>

    {{-- Navigation --}}
    <nav style="flex:1; padding:10px 8px;">
      <p style="font-size:9px; font-weight:600; letter-spacing:1.4px; text-transform:uppercase; color:#6a9a7e; padding:0 10px 8px;">Menu</p>

      <a href="{{ route('siswa.dashboard') }}"
         class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>

      <a href="{{ route('siswa.quiz.index') }}"
         class="nav-item {{ request()->routeIs('siswa.quiz.index') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
        </svg>
        Quiz Hafalan
      </a>

      <a href="{{ route('siswa.leaderboard') }}"
         class="nav-item {{ request()->routeIs('siswa.leaderboard') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
          <path d="M4 22h16"/>
          <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
          <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
          <path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>
        </svg>
        Papan Peringkat
      </a>

      <a href="{{ route('siswa.lencana.index') }}"
         class="nav-item {{ request()->routeIs('siswa.lencana*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <circle cx="12" cy="8" r="6"/>
          <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
        Lencana Saya
      </a>

      <a href="{{ route('siswa.riwayat') }}"
         class="nav-item {{ request()->routeIs('siswa.riwayat*') ? 'nav-active' : '' }}"
         style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#b0cfc0; text-decoration:none; margin-bottom:1px;">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        Riwayat Quiz
      </a>
    </nav>

    {{-- Logout --}}
    <div style="padding:10px 8px 16px; border-top:1px solid rgba(255,255,255,0.08);">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
          class="nav-item"
          style="display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:7px; font-size:12px; font-weight:500; color:#c07a74; background:none; border:none; cursor:pointer; width:100%; text-align:left;">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Keluar
        </button>
      </form>
    </div>

  </aside>

  <main style="flex:1; display:flex; flex-direction:column; overflow-y:auto;">

    {{-- Page Header --}}
    @section('header')
    <header class="page-header" style="padding:18px 28px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; gap:12px;">
      <div style="display:flex; align-items:center; gap:12px; min-width:0;">
        
        <label for="sidebar-toggle" class="md:hidden text-emerald-800 hover:text-emerald-950 cursor-pointer p-1.5 bg-emerald-50 rounded-lg flex-shrink-0">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </label>

        <div style="min-width:0;">
          <h1 style="font-size:16px; font-weight:700; color:#1e3a2a; letter-spacing:-0.2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0;">
            @yield('page_title', 'Dashboard')
          </h1>
          <p class="hidden sm:block" style="font-size:12px; color:#6b7c74; margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
            @yield('page_subtitle', '')
          </p>
        </div>
      </div>
      <div style="flex-shrink:0;">@yield('header_actions')</div>
    </header>
    @show

    {{-- Flash: Success --}}
    @if(session('success'))
      <div class="flash-success" style="margin:16px 28px 0; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:9px; font-size:13px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif

    {{-- Flash: Error --}}
    @if(session('error'))
      <div class="flash-error" style="margin:16px 28px 0; display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:9px; font-size:13px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
      <div class="flash-error" style="margin:16px 28px 0; padding:10px 14px; border-radius:9px; font-size:13px;">
        @foreach($errors->all() as $error)
          <div style="display:flex; align-items:center; gap:7px; {{ !$loop->first ? 'margin-top:4px;' : '' }}">
            <span style="width:5px; height:5px; background:#b83232; border-radius:50%; flex-shrink:0;"></span>
            {{ $error }}
          </div>
        @endforeach
      </div>
    @endif

    {{-- Main page content --}}
    <div class="main-content-area" style="flex:1; padding:22px 28px;">
      @yield('content')
    </div>

  </main>

  {{-- Modals slot --}}
  @yield('modals')

  {{-- Base script --}}
  <script>
    window.addEventListener('click', function(e) {
      document.querySelectorAll('.modal-backdrop').forEach(function(el) {
        if (e.target === el) el.classList.add('hidden');
      });
    });
  </script>

  @yield('scripts')
</body>
</html>
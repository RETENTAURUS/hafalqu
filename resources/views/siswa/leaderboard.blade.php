@extends('layouts.siswa')

@section('title', 'Papan Peringkat')

@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&display=swap');

  /* Container Utama dengan Background Khas HafalQU */
  .hq-leaderboard-wrap {
    --forest: #1a3a2e;
    --leaf: #2d7a5f;
    --leaf-light: #6fc9a8;
    --gold: #d4a843;
    --gold-light: #ffe9a8;
    --cream: #fbf9f3;
    
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px;
    border-radius: 32px;
    position: relative;
    overflow: hidden;
    background-color: var(--cream);
    
    /* Efek Pola Titik Peta Petualangan */
    background-image: radial-gradient(#e6dfd1 1.5px, transparent 1.5px);
    background-size: 24px 24px;
    box-shadow: inset 0 0 40px rgba(26,58,46,0.03), 0 8px 24px rgba(0,0,0,0.02);
  }

  /* Ornamen Mengapung Organik */
  .hq-leaderboard-wrap::before, .hq-leaderboard-wrap::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    z-index: 0;
    pointer-events: none;
  }
  .hq-leaderboard-wrap::before {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(111,201,168,0.1) 0%, transparent 70%);
    top: -50px; left: -80px;
  }
  .hq-leaderboard-wrap::after {
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(255,233,168,0.18) 0%, transparent 70%);
    bottom: -50px; right: -80px;
  }

  /* Tipografi */
  .hq-leaderboard-wrap h1, 
  .hq-leaderboard-wrap h2, 
  .hq-leaderboard-wrap h3, 
  .hq-leaderboard-wrap .hq-display {
    font-family: 'Baloo 2', sans-serif;
  }

  /* Grid Layout Utama */
  .hq-grid-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    align-items: start;
    position: relative;
    z-index: 1;
  }

  /* Elemen Kartu */
  .hq-card {
    background: #fff;
    border-radius: 24px;
    border: 2px solid #e8e4dc;
    box-shadow: 0 6px 0px rgba(26,58,46,0.03);
    overflow: hidden;
  }

  /* Animasi Mahkota Juara #1 */
  @keyframes hq-crown-bounce {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-4px) scale(1.08); filter: drop-shadow(0 4px 6px rgba(212,168,67,0.4)); }
  }
  .hq-crown-king {
    animation: hq-crown-bounce 2s ease-in-out infinite;
    display: inline-block;
  }

  /* Responsif Layar Mobile / Tablet */
  @media (max-width: 992px) {
    .hq-grid-layout {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .hq-leaderboard-wrap {
      padding: 20px 14px;
      border-radius: 20px;
    }
    .hq-podium-container {
      padding: 24px 8px 0 !important;
      gap: 8px !important;
    }
    .hq-podium-box {
      max-width: 100px !important;
    }
    .hq-avatar-p1 {
      width: 52px !important; height: 52px !important;
    }
    .hq-avatar-p23 {
      width: 44px !important; height: 44px !important;
    }
    .hq-name-text {
      font-size: 0.75rem !important;
      max-width: 85px !important;
    }
  }
</style>

<div class="hq-leaderboard-wrap">

    {{-- Judul Halaman --}}
    <div style="margin-bottom: 24px; position: relative; z-index: 1;">
        <h1 style="font-size: 1.75rem; font-weight: 800; color: #1a3a2e; margin: 0; line-height: 1.2;">Papan Peringkat</h1>
    </div>

    <div class="hq-grid-layout">

        {{-- ===== LEADERBOARD KIRI ===== --}}
        <div class="hq-card">

            {{-- Header Papan Peringkat --}}
            <div style="padding: 20px 24px 12px; border-bottom: 2px solid #f4f2ee;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #1a3a2e; margin: 0;">
                    Leaderboard Kelas {{ $kelas->nama }}
                </h2>
                <p style="font-size: 0.85rem; color: #8a8272; font-weight: 600; margin: 2px 0 0;">Top siswa berdasarkan perolehan skor poin</p>
            </div>

            {{-- ===== PODIUM TOP 3 ===== --}}
            @if($leaderboard->count() > 0)
            <div class="hq-podium-container" style="padding: 24px 32px 0; display: flex; align-items: flex-end; justify-content: center; gap: 24px; min-height: 200px; background: linear-gradient(180deg, transparent, #faf8f5);">

                {{-- JUARA 2 --}}
                @if($leaderboard->count() >= 2)
                @php $s2 = $leaderboard[1]; $isSelf2 = $s2->id === auth()->id(); @endphp
                <div class="hq-podium-box" style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 130px;">
                    <div class="hq-avatar-p23" style="width: 56px; height: 56px; border-radius: 50%; background: #f3f4f6; overflow: hidden; border: 3px solid #b7ae9e; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        @if($s2->foto)
                            <img src="{{ asset('storage/'.$s2->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#b7ae9e" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        @endif
                    </div>
                    <p class="hq-name-text" style="font-size: 0.85rem; font-weight: 700; color: #374151; margin: 0 0 2px; text-align: center; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $s2->name }}
                    </p>
                    @if($isSelf2)
                        <span style="font-size: 0.65rem; font-weight: 800; background: #a7f3d0; color: #065f46; padding: 1px 6px; border-radius: 10px; margin-bottom: 4px;">Kamu</span>
                    @endif
                    <p style="font-size: 0.75rem; color: #7c8a80; font-weight: 700; margin: 0 0 8px;">
                        {{ number_format($s2->points) }} Pts
                    </p>
                    <div style="width: 100%; height: 60px; background: #b7ae9e; border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 #938a7a, inset 0 2px 0px rgba(255,255,255,0.2);">
                        <span class="hq-display" style="font-size: 1.3rem; font-weight: 800; color: #fff;">#2</span>
                    </div>
                </div>
                @endif

                {{-- JUARA 1 --}}
                @php $s1 = $leaderboard[0]; $isSelf1 = $s1->id === auth()->id(); @endphp
                <div class="hq-podium-box" style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 140px;">
                    <div class="hq-crown-king" style="font-size: 1.6rem; margin-bottom: 2px; line-height: 1;">👑</div>
                    <div class="hq-avatar-p1" style="width: 68px; height: 68px; border-radius: 50%; background: #fef3c7; overflow: hidden; border: 4px solid #d4a843; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 0 0 5px rgba(212,168,67,0.2), 0 6px 12px rgba(0,0,0,0.08);">
                        @if($s1->foto)
                            <img src="{{ asset('storage/'.$s1->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        @endif
                    </div>
                    <p class="hq-name-text" style="font-size: 0.9rem; font-weight: 800; color: #1a3a2e; margin: 0 0 2px; text-align: center; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $s1->name }}
                    </p>
                    @if($isSelf1)
                        <span style="font-size: 0.65rem; font-weight: 800; background: #a7f3d0; color: #065f46; padding: 1px 6px; border-radius: 10px; margin-bottom: 4px;">Kamu</span>
                    @endif
                    <p style="font-size: 0.8rem; color: #d4a843; font-weight: 800; margin: 0 0 8px;">
                        {{ number_format($s1->points) }} Pts
                    </p>
                    <div style="width: 100%; height: 85px; background: linear-gradient(180deg, #e8b84b 0%, #d4a843 100%); border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 0 #aa8126, inset 0 2px 0px rgba(255,255,255,0.3);">
                        <span class="hq-display" style="font-size: 1.6rem; font-weight: 800; color: #fff;">#1</span>
                    </div>
                </div>

                {{-- JUARA 3 --}}
                @if($leaderboard->count() >= 3)
                @php $s3 = $leaderboard[2]; $isSelf3 = $s3->id === auth()->id(); @endphp
                <div class="hq-podium-box" style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 130px;">
                    <div class="hq-avatar-p23" style="width: 56px; height: 56px; border-radius: 50%; background: #fdf6ec; overflow: hidden; border: 3px solid #c07a2a; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        @if($s3->foto)
                            <img src="{{ asset('storage/'.$s3->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#c07a2a" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        @endif
                    </div>
                    <p class="hq-name-text" style="font-size: 0.85rem; font-weight: 700; color: #374151; margin: 0 0 2px; text-align: center; max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $s3->name }}
                    </p>
                    @if($isSelf3)
                        <span style="font-size: 0.65rem; font-weight: 800; background: #a7f3d0; color: #065f46; padding: 1px 6px; border-radius: 10px; margin-bottom: 4px;">Kamu</span>
                    @endif
                    <p style="font-size: 0.75rem; color: #7c8a80; font-weight: 700; margin: 0 0 8px;">
                        {{ number_format($s3->points) }} Pts
                    </p>
                    <div style="width: 100%; height: 45px; background: #c07a2a; border-radius: 10px 10px 0 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 #945c1e, inset 0 2px 0px rgba(255,255,255,0.2);">
                        <span class="hq-display" style="font-size: 1.2rem; font-weight: 800; color: #fff;">#3</span>
                    </div>
                </div>
                @endif

            </div>
            @endif

            {{-- ===== LIST #4 dst ===== --}}
            <div style="padding: 16px 20px 20px; background: #fff;">
                @foreach($leaderboard as $index => $siswa)
                    @if($index >= 3)
                    @php $isSelf = $siswa->id === auth()->id(); @endphp
                    <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 14px; margin-bottom: 6px; background: {{ $isSelf ? '#e6f7f0' : '#f9fafb' }}; border: 2px solid {{ $isSelf ? '#6fc9a8' : '#eeece6' }}; box-shadow: 0 3px 0px {{ $isSelf ? 'rgba(111,201,168,0.2)' : 'rgba(0,0,0,0.02)' }};">

                        {{-- Nomor Urut --}}
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $isSelf ? 'var(--forest)' : '#e5e7eb' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <span style="font-size: 0.8rem; font-weight: 800; color: {{ $isSelf ? 'var(--gold)' : '#6b7280' }};">
                                {{ $index + 1 }}
                            </span>
                        </div>

                        {{-- Avatar Foto --}}
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: #e5e7eb; overflow: hidden; flex-shrink: 0; border: 2px solid {{ $isSelf ? '#6fc9a8' : '#e5e7eb' }}; display: flex; align-items: center; justify-content: center;">
                            @if($siswa->foto)
                                <img src="{{ asset('storage/'.$siswa->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Nama Siswa --}}
                        <span style="flex: 1; font-size: 0.9rem; font-weight: 700; color: {{ $isSelf ? 'var(--forest)' : '#374151' }}; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $siswa->name }}
                            @if($isSelf)
                                <span style="font-size: 0.68rem; font-weight: 800; color: #065f46; background: #a7f3d0; padding: 2px 8px; border-radius: 20px; margin-left: 4px; display: inline-block; vertical-align: middle;">Kamu</span>
                            @endif
                        </span>

                        {{-- Perolehan Poin --}}
                        <span class="hq-display" style="font-size: 0.95rem; font-weight: 800; color: {{ $isSelf ? 'var(--forest)' : '#5c6960' }}; flex-shrink: 0;">
                            {{ number_format($siswa->points) }} <span style="font-size: 0.75rem; font-weight: 600; color: #a0aab0;">Pts</span>
                        </span>
                    </div>
                    @endif
                @endforeach

                @if($leaderboard->isEmpty())
                    <p style="text-align: center; color: #8a8272; font-weight: 600; font-style: italic; padding: 32px 0; margin: 0;">
                        🍃 Belum ada data siswa di kelas ini.
                    </p>
                @endif
            </div>

            {{-- Footer Status Peringkat Pribadi --}}
            @if($myRank)
            <div style="border-top: 2px solid #f4f2ee; padding: 14px 24px; background: #faf8f5; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; color: #7c8a80; font-weight: 700;">Posisi Peringkatmu</span>
                <span class="hq-display" style="font-size: 1.1rem; font-weight: 800; color: var(--forest);">
                    #{{ $myRank }} <span style="font-size: 0.85rem; font-weight: 600; color: #8a8272;">dari {{ $leaderboard->count() }} siswa</span>
                </span>
            </div>
            @endif

        </div>

        {{-- ===== RIWAYAT POIN KANAN ===== --}}
        <div class="hq-card">

            {{-- Header Riwayat --}}
            <div style="padding: 16px 20px; border-bottom: 2px solid #f4f2ee;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1a3a2e; margin: 0;">
                    Riwayat Aktivitas
                </h3>
            </div>

            {{-- Ringkasan Total Skor Akun Pribadi --}}
            <div style="padding: 16px 20px; background: #f0fdf4; border-bottom: 2px solid #dcfce7; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; color: #525c55; font-weight: 700;">Koleksi Poinmu</span>
                <span class="hq-display" style="font-size: 1.4rem; font-weight: 800; color: #d4a843; line-height: 1;">
                    {{ number_format(auth()->user()->points ?? 0) }}
                    <span style="font-size: 0.8rem; font-weight: 700; color: #8a8272;"> Pts</span>
                </span>
            </div>

            {{-- Judul Mini Kolom --}}
            <div style="display: grid; grid-template-columns: 1fr auto; padding: 8px 20px; background: var(--forest);">
                <span style="font-size: 0.8rem; font-weight: 800; color: var(--gold-light);">Sumber Misi</span>
                <span style="font-size: 0.8rem; font-weight: 800; color: var(--gold-light);">Hadiah</span>
            </div>

            {{-- Baris Isi Log Riwayat Poin --}}
            <div style="max-height: 380px; overflow-y: auto;">
                @forelse($riwayatPoin as $item)
                <div style="display: grid; grid-template-columns: 1fr auto; padding: 12px 20px; border-bottom: 1px solid #f4f2ee; align-items: center; background: #fff;">
                    <div>
                        <p style="font-size: 0.85rem; font-weight: 700; color: #374151; margin: 0 0 2px;">
                            {{ $item->sumber }}
                        </p>
                        <p style="font-size: 0.72rem; color: #9ca3af; margin: 0; font-weight: 500;">
                            {{ $item->created_at->format('d M Y · H:i') }}
                        </p>
                    </div>
                    <span class="hq-display" style="font-size: 0.95rem; font-weight: 800; color: #16a34a; white-space: nowrap; margin-left: 12px;">
                        +{{ number_format($item->poin) }}
                    </span>
                </div>
                @empty
                <div style="padding: 40px 20px; text-align: center;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" style="margin: 0 auto 10px; display: block;">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p style="color: #8a8272; font-size: 0.85rem; font-weight: 700; margin: 0;">
                        Belum ada jejak poin.
                    </p>
                    <p style="color: #b7ae9e; font-size: 0.75rem; margin: 4px 0 0; font-weight: 500;">
                        Taklukkan pos ujian untuk meraih bonus pertamamu!
                    </p>
                </div>
                @endforelse
            </div>

        </div>

    </div>
</div>
@endsection
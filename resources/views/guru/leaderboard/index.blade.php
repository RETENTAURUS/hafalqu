@extends('layouts.guru')

@section('title', 'Papan Peringkat — HafalQU Guru')
@section('page_title', 'Papan Peringkat')
@section('page_subtitle', 'Kelas ' . ($kelas->nama ?? 'Belum ada kelas'))

@section('content')
<div style="padding: 2rem 2rem 3rem; max-width: 1200px; margin: 0 auto;">

    {{-- Page Title --}}
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.4rem; font-weight: 700; color: #1a3a2e; margin: 0;">
            Papan Peringkat
            @if($kelas)
                <span style="font-size: 1rem; font-weight: 400; color: #6b7280; margin-left: 0.5rem;">
                    Kelas {{ $kelas->nama }}
                </span>
            @endif
        </h1>
    </div>

    @if(!$kelas)
        <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; padding: 1.5rem; text-align: center;">
            <p style="color: #991b1b; font-weight: 600;">Anda belum memiliki kelas yang diampu.</p>
            <p style="color: #6b7280; font-size: 0.9rem;">Hubungi administrator untuk menambahkan kelas.</p>
        </div>
    @elseif($leaderboard->isEmpty())
        <div style="background: #f3f4f6; border-radius: 12px; padding: 3rem 1.5rem; text-align: center;">
            <p style="color: #6b7280;">Belum ada siswa di kelas ini.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start;">

            {{-- ===== LEADERBOARD KIRI ===== --}}
            <div style="background: #fff; border-radius: 16px; border: 1px solid #e5e7eb;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">

                {{-- Header --}}
                <div style="padding: 1.25rem 1.5rem 0.75rem;">
                    <h2 style="font-size: 1.1rem; font-weight: 700; color: #1a3a2e; margin: 0 0 0.2rem;">
                        🏆 Leaderboard Kelas {{ $kelas->nama }}
                    </h2>
                    <p style="font-size: 0.8rem; color: #9ca3af; margin: 0;">
                        Top siswa berdasarkan poin
                    </p>
                </div>

                {{-- ===== PODIUM TOP 3 ===== --}}
                @if($leaderboard->count() > 0)
                <div style="padding: 1.5rem 2rem 0; display: flex; align-items: flex-end;
                            justify-content: center; gap: 1.5rem; min-height: 180px;">

                    {{-- RANK 2 --}}
                    @if($leaderboard->count() >= 2)
                    @php $s2 = $leaderboard[1]; @endphp
                    <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 140px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%;
                                    background: #e5e7eb; overflow: hidden;
                                    border: 3px solid #9ca3af; display: flex; align-items: center;
                                    justify-content: center; margin-bottom: 0.5rem;">
                            @if($s2->foto)
                                <img src="{{ asset('storage/'.$s2->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            @endif
                        </div>
                        <p style="font-size: 0.8rem; font-weight: 600; color: #374151; margin: 0 0 0.1rem;
                                   text-align: center; max-width: 110px; overflow: hidden;
                                   text-overflow: ellipsis; white-space: nowrap;">
                            {{ $s2->name }}
                        </p>
                        <p style="font-size: 0.72rem; color: #6b7280; margin: 0 0 0.5rem;">
                            {{ number_format($s2->points) }} poin
                        </p>
                        <div style="width: 100%; height: 56px; background: #c0aa6b;
                                    border-radius: 6px 6px 0 0; display: flex; align-items: center;
                                    justify-content: center;">
                            <span style="font-size: 1.1rem; font-weight: 800; color: #fff;">#2</span>
                        </div>
                    </div>
                    @endif

                    {{-- RANK 1 --}}
                    @php $s1 = $leaderboard[0]; @endphp
                    <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 140px;">
                        <div style="font-size: 1.5rem; margin-bottom: 0.3rem; line-height: 1;">👑</div>
                        <div style="width: 64px; height: 64px; border-radius: 50%;
                                    background: #fef3c7; overflow: hidden;
                                    border: 3px solid #d4a843; display: flex; align-items: center;
                                    justify-content: center; margin-bottom: 0.5rem;
                                    box-shadow: 0 0 0 4px rgba(212,168,67,0.2);">
                            @if($s1->foto)
                                <img src="{{ asset('storage/'.$s1->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="1.5">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            @endif
                        </div>
                        <p style="font-size: 0.85rem; font-weight: 700; color: #1a3a2e; margin: 0 0 0.1rem;
                                   text-align: center; max-width: 120px; overflow: hidden;
                                   text-overflow: ellipsis; white-space: nowrap;">
                            {{ $s1->name }}
                        </p>
                        <p style="font-size: 0.75rem; color: #d4a843; font-weight: 600; margin: 0 0 0.5rem;">
                            {{ number_format($s1->points) }} poin
                        </p>
                        <div style="width: 100%; height: 80px;
                                    background: linear-gradient(180deg, #e8b84b 0%, #c49328 100%);
                                    border-radius: 6px 6px 0 0; display: flex; align-items: center;
                                    justify-content: center;
                                    box-shadow: 0 -2px 12px rgba(212,168,67,0.35);">
                            <span style="font-size: 1.3rem; font-weight: 800; color: #fff;">#1</span>
                        </div>
                    </div>

                    {{-- RANK 3 --}}
                    @if($leaderboard->count() >= 3)
                    @php $s3 = $leaderboard[2]; @endphp
                    <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 140px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%;
                                    background: #fef3c7; overflow: hidden;
                                    border: 3px solid #d97706; display: flex; align-items: center;
                                    justify-content: center; margin-bottom: 0.5rem;">
                            @if($s3->foto)
                                <img src="{{ asset('storage/'.$s3->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.5">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            @endif
                        </div>
                        <p style="font-size: 0.8rem; font-weight: 600; color: #374151; margin: 0 0 0.1rem;
                                   text-align: center; max-width: 110px; overflow: hidden;
                                   text-overflow: ellipsis; white-space: nowrap;">
                            {{ $s3->name }}
                        </p>
                        <p style="font-size: 0.72rem; color: #6b7280; margin: 0 0 0.5rem;">
                            {{ number_format($s3->points) }} poin
                        </p>
                        <div style="width: 100%; height: 40px; background: #c07a2a;
                                    border-radius: 6px 6px 0 0; display: flex; align-items: center;
                                    justify-content: center;">
                            <span style="font-size: 1.1rem; font-weight: 800; color: #fff;">#3</span>
                        </div>
                    </div>
                    @endif

                </div>
                @endif

                {{-- ===== LIST #4 dst ===== --}}
                <div style="padding: 0.75rem 1.25rem 1.25rem;">
                    @foreach($leaderboard as $index => $siswa)
                        @if($index >= 3)
                        <div style="display: flex; align-items: center; gap: 0.85rem;
                                    padding: 0.65rem 0.85rem; border-radius: 10px; margin-bottom: 0.35rem;
                                    background: #f9fafb; border: 1px solid transparent;">

                            {{-- Nomor --}}
                            <div style="width: 28px; height: 28px; border-radius: 50%;
                                        background: #e5e7eb; display: flex; align-items: center;
                                        justify-content: center; flex-shrink: 0;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: #6b7280;">
                                    {{ $index + 1 }}
                                </span>
                            </div>

                            {{-- Avatar --}}
                            <div style="width: 36px; height: 36px; border-radius: 50%;
                                        background: #e5e7eb; overflow: hidden; flex-shrink: 0;
                                        border: 2px solid #e5e7eb; display: flex; align-items: center;
                                        justify-content: center;">
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/'.$siswa->foto) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Nama --}}
                            <span style="flex: 1; font-size: 0.9rem; font-weight: 500; color: #374151;">
                                {{ $siswa->name }}
                            </span>

                            {{-- Poin --}}
                            <span style="font-size: 0.9rem; font-weight: 700; color: #6b7280;">
                                {{ number_format($siswa->points) }} poin
                            </span>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Footer total siswa --}}
                <div style="border-top: 1px solid #e5e7eb; padding: 0.75rem 1.5rem;
                            background: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #6b7280;">Total siswa</span>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #1a3a2e;">
                        {{ $leaderboard->count() }} siswa
                    </span>
                </div>

            </div>

            {{-- ===== RINGKASAN KELAS KANAN ===== --}}
            <div style="background: #fff; border-radius: 16px; border: 1px solid #e5e7eb;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">

                {{-- Header --}}
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1a3a2e; margin: 0;">
                        📊 Ringkasan Kelas
                    </h3>
                </div>

                {{-- Total Poin Siswa --}}
                <div style="padding: 1rem 1.25rem; background: #f0fdf4; border-bottom: 1px solid #dcfce7;
                            display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #6b7280;">Total Siswa</span>
                    <span style="font-size: 1.2rem; font-weight: 800; color: #1a3a2e;">
                        {{ $totalSiswa }}
                    </span>
                </div>

                {{-- Rata-rata Poin --}}
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;
                            display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #6b7280;">Rata-rata Poin</span>
                    <span style="font-size: 1.1rem; font-weight: 700; color: #d4a843;">
                        {{ number_format($avgPoints) }}
                    </span>
                </div>

                {{-- Poin Tertinggi --}}
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;
                            display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; color: #6b7280;">Poin Tertinggi</span>
                    <span style="font-size: 1.1rem; font-weight: 700; color: #16a34a;">
                        {{ number_format($highestPoints) }}
                    </span>
                </div>

                {{-- Footer --}}
                <div style="padding: 1rem 1.25rem; background: #f9fafb; text-align: center;">
                    <span style="font-size: 0.78rem; color: #9ca3af;">
                        {{ $leaderboard->count() > 0 ? 'Data terakhir diperbarui otomatis' : 'Belum ada data' }}
                    </span>
                </div>

            </div>

        </div>
    @endif
</div>
@endsection
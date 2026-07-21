@extends('layouts.siswa')

@section('title', 'Lencana Saya — HafalQU')
@section('page_title', '🏅 Lencana Saya')
@section('page_subtitle', 'Kumpulkan lencana dengan menyelesaikan tantangan')

@section('content')

@php
    // Urutan rarity, dipakai untuk mengurutkan badge dari yang paling langka
    $rarityOrder = ['platinum' => 0, 'gold' => 1, 'silver' => 2, 'bronze' => 3];
    $earnedSorted = $earnedBadges->sortBy(fn($b) => $rarityOrder[$b->level] ?? 99)->values();
    $lockedSorted = $lockedBadgesWithProgress->sortBy(fn($b) => $rarityOrder[$b->level] ?? 99)->values();

    // Ringkasan progres per level (bronze/silver/gold/platinum) — dari data yang sudah ada, tanpa query tambahan
    $earnedIds = $earnedBadges->pluck('id');
    $levelSummary = $allBadges->groupBy('level')->map(function ($items, $level) use ($earnedIds) {
        return [
            'level' => $level,
            'earned' => $items->whereIn('id', $earnedIds)->count(),
            'total' => $items->count(),
        ];
    })->sortBy(fn($row) => $rarityOrder[$row['level']] ?? 99);
@endphp

<style>
    /* Efek kilau yang menyapu kartu lencana bertingkat tinggi yang sudah diraih */
    @keyframes badge-shine-sweep {
        0%   { transform: translateX(-120%) rotate(20deg); opacity: 0; }
        15%  { opacity: 0.5; }
        50%  { opacity: 0.5; }
        100% { transform: translateX(220%) rotate(20deg); opacity: 0; }
    }
    .badge-shine::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 0;
        width: 30%;
        height: 200%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,0.75), transparent);
        animation: badge-shine-sweep 3.2s ease-in-out infinite;
        pointer-events: none;
    }
</style>

{{-- ─── HERO ─── --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-50/80 via-emerald-50/60 to-cyan-50/40 border border-teal-100/60 p-6 md:p-8 mb-8">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <span class="bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">🏅 Lencana Saya</span>
                <span class="text-sm font-medium bg-teal-100 text-teal-700 px-3 py-0.5 rounded-full">{{ $allBadges->count() }} Total</span>
            </h2>
            <p class="text-sm text-slate-500 mt-0.5 flex items-center gap-1.5">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                Kumpulkan lencana dengan menyelesaikan tantangan
            </p>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-500">Progress</span>
            <div class="w-32 h-2 bg-slate-200/80 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-full transition-all duration-700"
                     style="width: {{ round(($earnedBadges->count() / max(1, $allBadges->count())) * 100) }}%"></div>
            </div>
            <span class="font-semibold text-slate-700 min-w-[3rem] text-right">{{ round(($earnedBadges->count() / max(1, $allBadges->count())) * 100) }}%</span>
        </div>
    </div>
</div>

{{-- ─── STATISTIK ─── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'Lencana Diraih', 'value' => $earnedBadges->count(), 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'grad' => 'from-teal-100 to-emerald-100', 'text' => 'teal-600'],
            ['label' => 'Lencana Terkunci', 'value' => $lockedBadgesWithProgress->count(), 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z', 'grad' => 'from-amber-100 to-orange-100', 'text' => 'amber-600'],
            ['label' => 'Total Lencana', 'value' => $allBadges->count(), 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0l-4.725 2.885a.562.562 0 01-.84-.61l1.285-5.385a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'grad' => 'from-violet-100 to-purple-100', 'text' => 'violet-600'],
            ['label' => 'Progress', 'value' => round(($earnedBadges->count() / max(1, $allBadges->count())) * 100).'%', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z', 'grad' => 'from-pink-100 to-rose-100', 'text' => 'pink-600'],
        ];
    @endphp
    @foreach($stats as $stat)
    <div class="bg-white/70 backdrop-blur border border-white/30 rounded-xl p-4 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
        <div class="w-10 h-10 mx-auto rounded-full bg-gradient-to-br {{ $stat['grad'] }} flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5 text-{{ $stat['text'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
            </svg>
        </div>
        <p class="text-2xl font-extrabold text-slate-800 tabular-nums">{{ $stat['value'] }}</p>
        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- ─── RINGKASAN PER LEVEL (rarity) ─── --}}
@if($levelSummary->isNotEmpty())
<div class="mb-8 bg-white/70 backdrop-blur border border-white/30 rounded-xl p-4">
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Koleksi per Level</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach($levelSummary as $row)
        @php
            $pct = $row['total'] > 0 ? round(($row['earned'] / $row['total']) * 100) : 0;
            $complete = $row['total'] > 0 && $row['earned'] === $row['total'];
        @endphp
        <div class="rounded-lg border border-slate-100 p-3 {{ $complete ? 'bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-200' : '' }}">
            <div class="flex items-center justify-between mb-1.5">
                <x-level-tag :level="$row['level']" size="text-[0.65rem]" />
                @if($complete)
                    <span class="text-[0.65rem] font-bold text-emerald-600">✓ Lengkap</span>
                @endif
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">{{ $row['earned'] }} / {{ $row['total'] }} diraih</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ─── LENCANA DIRAIH ─── --}}
@if($earnedSorted->count() > 0)
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="text-xl">🏆</span> Lencana Diraih
            <span class="text-sm font-medium bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full">{{ $earnedSorted->count() }}</span>
        </h3>
        <span class="text-xs text-slate-400">✨ Koleksi terbaikmu, dari yang paling langka</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($earnedSorted as $badge)
        @php $isRare = in_array($badge->level, ['gold', 'platinum']); @endphp
        <div data-earned-card class="relative bg-white/90 border rounded-xl p-4 text-center hover:-translate-y-2 hover:scale-[1.02] hover:shadow-xl transition-all duration-300 overflow-hidden {{ $isRare ? 'border-yellow-200/70' : 'border-emerald-200/60' }} {{ $isRare ? 'badge-shine' : '' }}">
            <div class="flex justify-center mb-2.5">
                <x-badge-icon :badge="$badge" size="w-16 h-16" :glow="true" />
            </div>
            <p class="font-semibold text-sm text-slate-800 leading-tight">{{ $badge->name }}</p>
            <div class="flex justify-center mt-1.5">
                <x-level-tag :level="$badge->level" />
            </div>
            <div class="mt-2">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50/80 px-2.5 py-0.5 rounded-full border border-emerald-200/50">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                    Diraih
                </span>
            </div>
            @if($badge->pivot && $badge->pivot->earned_at)
            <p class="text-[10px] text-slate-400 mt-1.5 font-medium">{{ $badge->pivot->earned_at->diffForHumans() }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ─── LENCANA TERKUNCI ─── --}}
@if($lockedSorted->count() > 0)
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="text-xl">🔒</span> Lencana Terkunci
            <span class="text-sm font-medium bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full">{{ $lockedSorted->count() }}</span>
        </h3>
        <span class="text-xs text-slate-400">Terus berlatih untuk membuka</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach($lockedSorted as $badge)
        @php $almostThere = $badge->progress >= 80 && $badge->progress < 100; @endphp
        <div class="relative bg-white/60 border border-slate-200/70 rounded-xl p-4 text-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
            @if($almostThere)
            <span class="absolute -top-2 left-1/2 -translate-x-1/2 text-[0.6rem] font-bold uppercase tracking-wide bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full shadow-sm z-10 animate-pulse">
                🔥 Hampir selesai!
            </span>
            @endif
            <div class="relative inline-block mb-2.5">
                <x-badge-icon :badge="$badge" size="w-16 h-16" :earned="false" />
                <span class="absolute -bottom-1 -right-1 text-sm bg-white rounded-full w-5 h-5 flex items-center justify-center shadow">🔒</span>
            </div>
            <p class="font-semibold text-sm text-slate-700 leading-tight">{{ $badge->name }}</p>
            <div class="flex justify-center mt-1.5">
                <x-level-tag :level="$badge->level" />
            </div>
            {{-- Progress Bar --}}
            <div class="mt-3">
                <div class="relative w-full h-2 bg-slate-200/70 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-full transition-all duration-700" style="width: {{ $badge->progress }}%"></div>
                    @if($badge->progress > 0 && $badge->progress < 100)
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse" style="background-size: 200% 100%;"></div>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-[10px] font-medium text-slate-500">{{ $badge->progress }}%</span>
                    <span class="text-[10px] text-slate-400 truncate max-w-[60%]">{{ $badge->progress_text }}</span>
                </div>
            </div>
            <div class="mt-2">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-100/70 px-2.5 py-0.5 rounded-full border border-slate-200/50">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    Terkunci
                </span>
            </div>
            {{-- Tooltip syarat (muncul saat hover) --}}
            <div class="absolute inset-0 rounded-xl bg-black/60 backdrop-blur-sm flex items-center justify-center p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                <div class="bg-white rounded-xl p-3 shadow-xl max-w-[200px] w-full">
                    <p class="text-xs font-semibold text-slate-700 mb-1">🔑 Syarat</p>
                    <p class="text-xs text-slate-500">{{ $badge->criteria_label ?? 'Selesaikan tantangan' }} <span class="font-medium text-slate-700">minimal {{ $badge->criteria_value }}</span></p>
                    <div class="mt-2 w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full transition-all duration-700" style="width: {{ $badge->progress }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 text-right">{{ $badge->progress }}% selesai</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ─── EMPTY STATE ─── --}}
@if($allBadges->isEmpty())
<div class="text-center py-16 px-4">
    <div class="text-6xl mb-4 opacity-40 relative inline-block">
        🏅
        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber-400/30 blur-xl"></span>
    </div>
    <p class="text-slate-500 font-medium text-lg">Belum ada lencana tersedia</p>
    <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Ikuti tantangan dan raih lencana untuk menunjukkan kemampuanmu!</p>
</div>
@endif

{{-- ─── JAVASCRIPT RINGAN (hanya untuk confetti opsional) ─── --}}
@if($earnedSorted->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confetti sederhana untuk lencana diraih (random)
        const cards = document.querySelectorAll('[data-earned-card]');
        const colors = ['#10b981','#14b8a6','#f59e0b','#8b5cf6','#ec4899'];
        cards.forEach((card, idx) => {
            if (Math.random() > 0.5) return;
            for (let i=0; i<5; i++) {
                const piece = document.createElement('div');
                const size = 4 + Math.random()*6;
                Object.assign(piece.style, {
                    position: 'absolute',
                    width: size+'px',
                    height: (size*(0.6+Math.random()*0.8))+'px',
                    background: colors[Math.floor(Math.random()*colors.length)],
                    left: (10+Math.random()*80)+'%',
                    top: (10+Math.random()*30)+'%',
                    borderRadius: Math.random()>0.5?'50%':'2px',
                    opacity: 0,
                    pointerEvents: 'none',
                    transform: 'rotate('+(Math.random()*720)+'deg)',
                    animation: `confetti-fall ${0.8+Math.random()*0.6}s ease-out ${0.1+Math.random()*0.5}s forwards`
                });
                card.appendChild(piece);
                setTimeout(() => piece.remove(), 2000);
            }
        });
        // tambahkan keyframe confetti-fall jika belum ada
        if (!document.getElementById('confetti-style')) {
            const style = document.createElement('style');
            style.id = 'confetti-style';
            style.textContent = `@keyframes confetti-fall { 0% { opacity:1; transform: translateY(-10px) rotate(0deg); } 100% { opacity:0; transform: translateY(60px) rotate(720deg); } }`;
            document.head.appendChild(style);
        }
    });
</script>
@endif

@endsection
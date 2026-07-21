{{--
    Komponen badge/lencana.
    Pakai: <x-badge-icon :badge="$badge" size="w-16 h-16" />

    Menampilkan:
    - Gambar upload ($badge->image) jika ada, disimpan di storage/app/public/badges
    - Fallback ke emoji ($badge->icon) jika belum ada gambar
    - Border warna sesuai level (bronze/silver/gold/platinum)
    - Efek abu-abu (grayscale) kalau lencana belum aktif / belum diraih siswa
--}}
@props([
    'badge',
    'size' => 'w-16 h-16',
    'earned' => true,  // set false di halaman siswa kalau badge ini belum dicapai
    'glow' => false,   // aktifkan efek cahaya untuk badge gold/platinum yang sudah diraih
])

@php
    $levelRing = match($badge->level) {
        'bronze' => 'ring-amber-600',
        'silver' => 'ring-slate-400',
        'gold' => 'ring-yellow-400',
        'platinum' => 'ring-cyan-300',
        default => 'ring-slate-200',
    };

    $glowShadow = match(true) {
        !$earned => '',
        $badge->level === 'platinum' => 'shadow-[0_0_18px_rgba(103,232,249,0.65)]',
        $badge->level === 'gold' => 'shadow-[0_0_14px_rgba(250,204,21,0.55)]',
        default => 'shadow-sm',
    };

    $hasImage = !empty($badge->image) && \Illuminate\Support\Facades\Storage::disk('public')->exists('badges/' . $badge->image);
@endphp

<div
    class="{{ $size }} rounded-full ring-2 {{ $levelRing }} flex items-center justify-center overflow-hidden bg-white {{ $earned ? ($glow ? $glowShadow : 'shadow-sm') : 'grayscale opacity-40' }} transition-shadow duration-300"
    title="{{ $badge->name }}"
>
    @if($hasImage)
        <img
            src="{{ asset('storage/badges/' . $badge->image) }}"
            alt="{{ $badge->name }}"
            class="w-full h-full object-cover"
            loading="lazy"
        >
    @else
        <span class="text-2xl leading-none">{{ $badge->icon ?? '🏅' }}</span>
    @endif
</div>
{{--
    Tag level lencana. Satu sumber kebenaran untuk warna & label level,
    dipakai di halaman guru maupun siswa supaya konsisten.
    Pakai: <x-level-tag :level="$badge->level" />
--}}
@props(['level', 'size' => 'text-xs'])

@php
    $map = [
        'bronze'   => ['label' => 'Perunggu', 'emoji' => '🥉', 'bg' => 'bg-amber-100',  'text' => 'text-amber-700'],
        'silver'   => ['label' => 'Perak',    'emoji' => '🥈', 'bg' => 'bg-slate-200',  'text' => 'text-slate-600'],
        'gold'     => ['label' => 'Emas',     'emoji' => '🥇', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
        'platinum' => ['label' => 'Platinum', 'emoji' => '💎', 'bg' => 'bg-cyan-100',   'text' => 'text-cyan-700'],
    ];
    $data = $map[$level] ?? ['label' => ucfirst($level ?? '-'), 'emoji' => '🏅', 'bg' => 'bg-slate-100', 'text' => 'text-slate-500'];
@endphp

<span class="inline-flex items-center gap-1 {{ $size }} font-semibold px-2 py-0.5 rounded-full {{ $data['bg'] }} {{ $data['text'] }}">
    <span>{{ $data['emoji'] }}</span> {{ $data['label'] }}
</span>
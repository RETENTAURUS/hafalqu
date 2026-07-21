@extends('layouts.guru')

@section('title', 'Sistem Poin — HafalQU')

@section('breadcrumb')
  <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
  </svg>
  <span class="text-slate-500">Guru</span>
  <svg class="w-3 h-3 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Sistem Poin</span>
@endsection

@section('content')

{{-- ═══ Summary Cards ═══ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
  @php
    $cards = [
      ['label'=>'Total Poin Dibagikan', 'value'=> number_format($totalPoinTerbagi), 'unit'=>'poin',         'color'=>'text-amber-600',   'bg'=>'bg-amber-50/60'],
      ['label'=>'Total Transaksi',      'value'=> number_format($totalTransaksi),   'unit'=>'log tercatat', 'color'=>'text-emerald-700', 'bg'=>'bg-emerald-50/60'],
      ['label'=>'Siswa Aktif',          'value'=> $siswaAktif,                      'unit'=>'pernah dapat', 'color'=>'text-blue-900',    'bg'=>'bg-blue-50/60'],
    ];
  @endphp
  @foreach($cards as $c)
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col justify-between">
    <p class="text-[10px] sm:text-[11px] font-semibold text-slate-400 tracking-wider uppercase mb-1 sm:mb-2">{{ $c['label'] }}</p>
    <p class="text-2xl sm:text-3xl font-extrabold {{ $c['color'] }} leading-tight">{{ $c['value'] }}</p>
    <p class="text-[10px] sm:text-xs text-slate-400 mt-1">{{ $c['unit'] }}</p>
  </div>
  @endforeach
</div>

{{-- ═══ Form Bobot Poin ═══ --}}
<form method="POST" action="{{ route('guru.poin.update-bobot') }}" id="form-bobot">
@csrf
<div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden mb-4 sm:mb-6 shadow-sm">

  <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div>
      <span class="text-xs sm:text-sm font-bold text-slate-800">Bobot Poin per Quiz</span>
      <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Poin diterima siswa = Skor × Bobot. Contoh: skor 80 × bobot 1.5 = 120 poin.</p>
    </div>
    <button type="submit"
            class="w-full sm:w-auto bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl px-4 py-2.5 sm:py-2 transition-colors shadow-sm flex items-center justify-center gap-1.5">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      <span>Simpan Semua Bobot</span>
    </button>
  </div>

  @if($quizzes->count())
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs sm:text-sm min-w-[640px]">
      <thead>
        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
          <th class="py-3 px-4 sm:px-5">Judul Quiz</th>
          <th class="py-3 px-4 sm:px-5 text-center">Rata-rata Skor</th>
          <th class="py-3 px-4 sm:px-5 text-center">Percobaan</th>
          <th class="py-3 px-4 sm:px-5 text-center w-44">Bobot Poin</th>
          <th class="py-3 px-4 sm:px-5 text-center">Est. Poin Rata-rata</th>
          <th class="py-3 px-4 sm:px-5 text-center">Rekalkulasi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-slate-700">
        @foreach($quizzes as $idx => $quiz)
        <tr class="hover:bg-slate-50/50 transition-colors">
          <input type="hidden" name="bobots[{{ $idx }}][id]" value="{{ $quiz['id'] }}">
          <td class="py-3.5 px-4 sm:px-5 font-medium text-slate-800">{{ $quiz['title'] }}</td>
          <td class="py-3.5 px-4 sm:px-5 text-center text-slate-500">{{ $quiz['avg_skor'] }}</td>
          <td class="py-3.5 px-4 sm:px-5 text-center text-slate-500">{{ $quiz['total_attempts'] }}x</td>
          <td class="py-3.5 px-4 sm:px-5 text-center">
            <div class="flex items-center justify-center gap-1.5">
              <button type="button" onclick="adjustBobot({{ $idx }}, -0.25)"
                      class="w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 active:scale-95 flex items-center justify-center font-bold text-slate-600 transition-all text-sm">
                −
              </button>
              <input type="number"
                     name="bobots[{{ $idx }}][bobot]"
                     id="bobot-{{ $idx }}"
                     value="{{ $quiz['bobot_poin'] }}"
                     min="0.1" max="10" step="0.25"
                     onchange="updateEstimasi({{ $idx }}, {{ $quiz['avg_skor'] }})"
                     class="w-16 text-center border border-slate-200 rounded-lg py-1.5 px-1 text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
              <button type="button" onclick="adjustBobot({{ $idx }}, 0.25)"
                      class="w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 active:scale-95 flex items-center justify-center font-bold text-slate-600 transition-all text-sm">
                +
              </button>
            </div>
          </td>
          <td class="py-3.5 px-4 sm:px-5 text-center">
            <span id="estimasi-{{ $idx }}" class="font-bold text-amber-600 text-xs sm:text-sm">
              {{ $quiz['estimasi_poin'] }}
            </span>
            <span class="text-[10px] text-slate-400"> poin</span>
          </td>
          <td class="py-3.5 px-4 sm:px-5 text-center">
            <button type="button"
                    onclick="konfirmasiRekal({{ $quiz['id'] }}, '{{ addslashes($quiz['title']) }}')"
                    class="bg-amber-50 hover:bg-amber-100 active:bg-amber-200 text-amber-800 font-semibold px-3 py-1.5 rounded-lg text-xs transition-colors">
              Hitung Ulang
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div class="p-8 text-center text-slate-400 text-xs sm:text-sm">Belum ada quiz aktif.</div>
  @endif

</div>
</form>

{{-- ═══ Tombol Rekalkulasi Semua ═══ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 mb-4 sm:mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3.5">
  <div>
    <p class="text-xs sm:text-sm font-bold text-slate-800">Rekalkulasi Semua Poin</p>
    <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">
      Hitung ulang poin seluruh siswa dari semua quiz berdasarkan bobot saat ini. Gunakan setelah mengubah bobot banyak quiz sekaligus.
    </p>
  </div>
  <button type="button" onclick="konfirmasiRekalSemua()"
          class="w-full sm:w-auto bg-red-700 hover:bg-red-800 active:bg-red-900 text-white font-semibold rounded-xl px-4 py-2.5 sm:py-2 text-xs sm:text-sm transition-colors shadow-sm flex-shrink-0">
    Hitung Ulang Semua
  </button>
</div>

{{-- ═══ Riwayat Poin ═══ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
  <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
    <div>
      <span class="text-xs sm:text-sm font-bold text-slate-800">Riwayat Poin Terbaru</span>
      <span class="text-[10px] sm:text-xs text-slate-400 ml-1 sm:ml-2">(50 transaksi terakhir)</span>
    </div>
  </div>
  @if($riwayat->count())

    {{-- MOBILE CARD VIEW --}}
    <div class="block md:hidden divide-y divide-slate-100">
      @foreach($riwayat as $log)
      <div class="p-3.5 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs flex-shrink-0">
            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
          </div>
          <div class="min-w-0">
            <h4 class="font-medium text-slate-800 text-xs truncate">{{ $log->user->name ?? '-' }}</h4>
            <div class="mt-0.5">
              @if(str_starts_with($log->sumber, 'quiz_'))
                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 rounded-full inline-block">
                  Quiz #{{ str_replace('quiz_', '', $log->sumber) }}
                </span>
              @else
                <span class="bg-slate-100 text-slate-600 text-[10px] font-semibold px-2 py-0.5 rounded-full inline-block">
                  {{ $log->sumber }}
                </span>
              @endif
            </div>
          </div>
        </div>
        <div class="text-right flex-shrink-0">
          <span class="font-extrabold text-amber-600 text-xs sm:text-sm block">+{{ $log->poin }}</span>
          <span class="text-[10px] text-slate-400 block mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
        </div>
      </div>
      @endforeach
    </div>

    {{-- DESKTOP TABLE VIEW --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs sm:text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
            <th class="py-3 px-5">Siswa</th>
            <th class="py-3 px-5">Sumber</th>
            <th class="py-3 px-5 text-center">Poin</th>
            <th class="py-3 px-5 text-right">Waktu</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @foreach($riwayat as $log)
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="py-3.5 px-5">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                </div>
                <span class="font-medium text-slate-800">{{ $log->user->name ?? '-' }}</span>
              </div>
            </td>
            <td class="py-3.5 px-5">
              @if(str_starts_with($log->sumber, 'quiz_'))
                <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                  Quiz #{{ str_replace('quiz_', '', $log->sumber) }}
                </span>
              @else
                <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                  {{ $log->sumber }}
                </span>
              @endif
            </td>
            <td class="py-3.5 px-5 text-center font-bold text-amber-600">
              +{{ $log->poin }}
            </td>
            <td class="py-3.5 px-5 text-right text-slate-400 text-xs">
              {{ $log->created_at->diffForHumans() }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="p-8 text-center text-slate-400 text-xs sm:text-sm">Belum ada riwayat poin.</div>
  @endif
</div>

{{-- ═══ Modal Konfirmasi Rekalkulasi ═══ --}}
<div id="modal-rekal" class="modal-backdrop hidden fixed inset-0 bg-slate-900/60 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 backdrop-blur-sm">
  <div class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-xl overflow-hidden p-5 sm:p-6 space-y-4">
    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    </div>
    <div>
      <h3 id="modal-rekal-title" class="text-sm sm:text-base font-bold text-slate-800"></h3>
      <p id="modal-rekal-body" class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed"></p>
    </div>
    <div class="flex flex-col-reverse sm:flex-row gap-2 pt-2">
      <button type="button" onclick="tutupModal()"
              class="w-full sm:flex-1 py-2.5 border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-50 transition-colors">
        Batal
      </button>
      <form id="form-rekal" method="POST" action="{{ route('guru.poin.rekalkulasi') }}" class="w-full sm:flex-1">
        @csrf
        <input type="hidden" id="rekal-quiz-id" name="quiz_id" value="">
        <button type="submit"
                class="w-full py-2.5 bg-[#115E59] hover:bg-teal-800 active:bg-teal-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Ya, Hitung Ulang
        </button>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  // ── Adjust bobot ± tombol
  function adjustBobot(idx, delta) {
    const input = document.getElementById('bobot-' + idx);
    let val = parseFloat(input.value) + delta;
    val = Math.max(0.1, Math.min(10, Math.round(val * 100) / 100));
    input.value = val.toFixed(2);
    updateEstimasi(idx, parseFloat(input.dataset.avg || 0));
  }

  // ── Update estimasi poin live
  function updateEstimasi(idx, avgSkor) {
    const bobot = parseFloat(document.getElementById('bobot-' + idx).value) || 1;
    const est   = Math.round(avgSkor * bobot);
    document.getElementById('estimasi-' + idx).textContent = est;
  }

  // Simpan avg skor di input untuk dipakai adjustBobot
  document.querySelectorAll('[id^="bobot-"]').forEach(function(input) {
    const idx     = input.id.replace('bobot-', '');
    const estEl   = document.getElementById('estimasi-' + idx);
    const bobot   = parseFloat(input.value) || 1;
    const est     = parseInt(estEl?.textContent) || 0;
    input.dataset.avg = bobot > 0 ? (est / bobot).toFixed(1) : 0;
  });

  // ── Modal rekalkulasi per quiz
  function konfirmasiRekal(quizId, judul) {
    document.getElementById('modal-rekal-title').textContent = 'Hitung Ulang Poin: ' + judul;
    document.getElementById('modal-rekal-body').textContent  =
      'Poin semua siswa untuk quiz ini akan dihitung ulang berdasarkan bobot saat ini. Lanjutkan?';
    document.getElementById('rekal-quiz-id').value = quizId;
    document.getElementById('modal-rekal').classList.remove('hidden');
    document.getElementById('modal-rekal').style.display = 'flex';
  }

  // ── Modal rekalkulasi semua
  function konfirmasiRekalSemua() {
    document.getElementById('modal-rekal-title').textContent = 'Hitung Ulang Semua Poin';
    document.getElementById('modal-rekal-body').textContent  =
      'Seluruh poin siswa dari semua quiz akan dihitung ulang. Proses ini tidak dapat dibatalkan. Lanjutkan?';
    document.getElementById('rekal-quiz-id').value = '';
    document.getElementById('modal-rekal').classList.remove('hidden');
    document.getElementById('modal-rekal').style.display = 'flex';
  }

  function tutupModal() {
    document.getElementById('modal-rekal').classList.add('hidden');
    document.getElementById('modal-rekal').style.display = 'none';
  }
</script>
@endsection
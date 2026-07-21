@extends('layouts.guru')

@section('title', 'Sistem Poin — HafalQU')

@section('breadcrumb')
  <svg style="width:15px;height:15px;color:#6b7c74;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
  </svg>
  <span style="color:#6b7c74;">Guru</span>
  <svg style="width:13px;height:13px;color:#c8d5cc;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Sistem Poin</span>
@endsection

@section('content')

{{-- ═══ Summary Cards ═══ --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
  @php
    $cards = [
      ['label'=>'Total Poin Dibagikan', 'value'=> number_format($totalPoinTerbagi), 'unit'=>'poin',        'color'=>'#d4a843', 'bg'=>'#fdf3e0'],
      ['label'=>'Total Transaksi',      'value'=> number_format($totalTransaksi),   'unit'=>'log tercatat', 'color'=>'#2d7a5f', 'bg'=>'#e1f5f0'],
      ['label'=>'Siswa Aktif',          'value'=> $siswaAktif,                      'unit'=>'pernah dapat poin', 'color'=>'#1a3a8e', 'bg'=>'#e6f1fb'],
    ];
  @endphp
  @foreach($cards as $c)
  <div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;padding:16px 18px;">
    <p style="font-size:10px;font-weight:600;color:#6b7c74;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">{{ $c['label'] }}</p>
    <p style="font-size:28px;font-weight:800;color:{{ $c['color'] }};line-height:1;">{{ $c['value'] }}</p>
    <p style="font-size:11px;color:#a09882;margin-top:3px;">{{ $c['unit'] }}</p>
  </div>
  @endforeach
</div>

{{-- ═══ Form Bobot Poin ═══ --}}
<form method="POST" action="{{ route('guru.poin.update-bobot') }}" id="form-bobot">
@csrf
<div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;margin-bottom:16px;">

  <div style="padding:14px 18px 12px;border-bottom:1px solid #f0ede6;display:flex;align-items:center;justify-content:space-between;">
    <div>
      <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Bobot Poin per Quiz</span>
      <p style="font-size:11px;color:#6b7c74;margin-top:2px;">Poin diterima siswa = Skor × Bobot. Contoh: skor 80 × bobot 1.5 = 120 poin.</p>
    </div>
    <button type="submit"
            style="background:#1a3a2e;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:12px;font-weight:600;cursor:pointer;">
      Simpan Semua Bobot
    </button>
  </div>

  @if($quizzes->count())
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
      <thead>
        <tr style="background:#faf8f5;border-bottom:1px solid #f0ede6;">
          <th style="padding:10px 18px;text-align:left;font-weight:600;color:#6b7c74;">Judul Quiz</th>
          <th style="padding:10px 18px;text-align:center;font-weight:600;color:#6b7c74;">Rata-rata Skor</th>
          <th style="padding:10px 18px;text-align:center;font-weight:600;color:#6b7c74;">Percobaan</th>
          <th style="padding:10px 18px;text-align:center;font-weight:600;color:#6b7c74;width:160px;">Bobot Poin</th>
          <th style="padding:10px 18px;text-align:center;font-weight:600;color:#6b7c74;">Est. Poin Rata-rata</th>
          <th style="padding:10px 18px;text-align:center;font-weight:600;color:#6b7c74;">Rekalkukasi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quizzes as $idx => $quiz)
        <tr style="border-bottom:1px solid #f5f2ec;">
          <input type="hidden" name="bobots[{{ $idx }}][id]" value="{{ $quiz['id'] }}">
          <td style="padding:10px 18px;color:#2d3a33;font-weight:500;">{{ $quiz['title'] }}</td>
          <td style="padding:10px 18px;text-align:center;color:#6b7c74;">{{ $quiz['avg_skor'] }}</td>
          <td style="padding:10px 18px;text-align:center;color:#6b7c74;">{{ $quiz['total_attempts'] }}x</td>
          <td style="padding:10px 18px;text-align:center;">
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
              <button type="button" onclick="adjustBobot({{ $idx }}, -0.25)"
                      style="width:26px;height:26px;border-radius:6px;border:1px solid #ddd;background:#fff;
                             font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#4a5a52;">−</button>
              <input type="number"
                     name="bobots[{{ $idx }}][bobot]"
                     id="bobot-{{ $idx }}"
                     value="{{ $quiz['bobot_poin'] }}"
                     min="0.1" max="10" step="0.25"
                     onchange="updateEstimasi({{ $idx }}, {{ $quiz['avg_skor'] }})"
                     style="width:64px;text-align:center;border:1px solid #ddd;border-radius:7px;
                            padding:5px 6px;font-size:13px;font-weight:600;color:#1e3a2a;">
              <button type="button" onclick="adjustBobot({{ $idx }}, 0.25)"
                      style="width:26px;height:26px;border-radius:6px;border:1px solid #ddd;background:#fff;
                             font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#4a5a52;">+</button>
            </div>
          </td>
          <td style="padding:10px 18px;text-align:center;">
            <span id="estimasi-{{ $idx }}"
                  style="font-weight:700;color:#d4a843;font-size:13px;">
              {{ $quiz['estimasi_poin'] }}
            </span>
            <span style="font-size:10px;color:#a09882;"> poin</span>
          </td>
          <td style="padding:10px 18px;text-align:center;">
            <button type="button"
                    onclick="konfirmasiRekal({{ $quiz['id'] }}, '{{ addslashes($quiz['title']) }}')"
                    style="background:#fdf3e0;color:#a06900;border:none;border-radius:6px;
                           padding:5px 12px;font-size:11px;font-weight:600;cursor:pointer;">
              Hitung Ulang
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div style="padding:28px;text-align:center;color:#a09882;font-size:13px;">Belum ada quiz aktif.</div>
  @endif

</div>
</form>

{{-- ═══ Tombol Rekalkulasi Semua ═══ --}}
<div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;padding:16px 18px;margin-bottom:16px;
            display:flex;align-items:center;justify-content:space-between;">
  <div>
    <p style="font-size:13px;font-weight:700;color:#1e3a2a;">Rekalkulasi Semua Poin</p>
    <p style="font-size:11px;color:#6b7c74;margin-top:2px;">
      Hitung ulang poin seluruh siswa dari semua quiz berdasarkan bobot saat ini. Gunakan setelah mengubah bobot banyak quiz sekaligus.
    </p>
  </div>
  <button type="button" onclick="konfirmasiRekalSemua()"
          style="background:#b83232;color:#fff;border:none;border-radius:8px;
                 padding:9px 18px;font-size:12px;font-weight:600;cursor:pointer;white-space:nowrap;">
    Hitung Ulang Semua
  </button>
</div>

{{-- ═══ Riwayat Poin ═══ --}}
<div style="background:#fff;border-radius:10px;border:1px solid #e8e4dc;overflow:hidden;">
  <div style="padding:14px 18px 12px;border-bottom:1px solid #f0ede6;">
    <span style="font-size:13px;font-weight:700;color:#1e3a2a;">Riwayat Poin Terbaru</span>
    <span style="font-size:11px;color:#a09882;margin-left:8px;">50 transaksi terakhir</span>
  </div>
  @if($riwayat->count())
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
      <thead>
        <tr style="background:#faf8f5;border-bottom:1px solid #f0ede6;">
          <th style="padding:9px 18px;text-align:left;font-weight:600;color:#6b7c74;">Siswa</th>
          <th style="padding:9px 18px;text-align:left;font-weight:600;color:#6b7c74;">Sumber</th>
          <th style="padding:9px 18px;text-align:center;font-weight:600;color:#6b7c74;">Poin</th>
          <th style="padding:9px 18px;text-align:right;font-weight:600;color:#6b7c74;">Waktu</th>
        </tr>
      </thead>
      <tbody>
        @foreach($riwayat as $log)
        <tr style="border-bottom:1px solid #f5f2ec;">
          <td style="padding:9px 18px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:26px;height:26px;border-radius:50%;background:#d4a843;display:flex;
                          align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#5a3200;flex-shrink:0;">
                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
              </div>
              <span style="font-weight:500;color:#2d3a33;">{{ $log->user->name ?? '-' }}</span>
            </div>
          </td>
          <td style="padding:9px 18px;color:#6b7c74;">
            @if(str_starts_with($log->sumber, 'quiz_'))
              <span style="background:#e1f5f0;color:#1a7a5e;font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;">
                Quiz #{{ str_replace('quiz_', '', $log->sumber) }}
              </span>
            @else
              <span style="background:#f0ede6;color:#6b7c74;font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;">
                {{ $log->sumber }}
              </span>
            @endif
          </td>
          <td style="padding:9px 18px;text-align:center;font-weight:700;color:#d4a843;">
            +{{ $log->poin }}
          </td>
          <td style="padding:9px 18px;text-align:right;color:#a09882;font-size:11px;">
            {{ $log->created_at->diffForHumans() }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div style="padding:28px;text-align:center;color:#a09882;font-size:13px;">Belum ada riwayat poin.</div>
  @endif
</div>

{{-- ═══ Modal Konfirmasi Rekalkulasi ═══ --}}
<div id="modal-rekal" class="modal-backdrop hidden"
     style="position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;
            display:flex;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:14px;padding:28px;width:360px;
              box-shadow:0 20px 60px rgba(0,0,0,0.15);">
    <div style="width:44px;height:44px;border-radius:50%;background:#fdf3e0;display:flex;
                align-items:center;justify-content:center;margin-bottom:14px;">
      <svg style="width:22px;height:22px;color:#a06900;" viewBox="0 0 24 24" fill="none" stroke="#a06900" stroke-width="2">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    </div>
    <p id="modal-rekal-title" style="font-size:15px;font-weight:700;color:#1e3a2a;margin-bottom:6px;"></p>
    <p id="modal-rekal-body"  style="font-size:13px;color:#6b7c74;margin-bottom:22px;line-height:1.5;"></p>
    <div style="display:flex;gap:10px;">
      <button onclick="tutupModal()"
              style="flex:1;padding:9px;border-radius:8px;border:1px solid #ddd;background:#fff;
                     font-size:13px;font-weight:500;color:#4a5a52;cursor:pointer;">Batal</button>
      <form id="form-rekal" method="POST" action="{{ route('guru.poin.rekalkulasi') }}" style="flex:1;">
        @csrf
        <input type="hidden" id="rekal-quiz-id" name="quiz_id" value="">
        <button type="submit"
                style="width:100%;padding:9px;border-radius:8px;border:none;background:#1a3a2e;
                       font-size:13px;font-weight:600;color:#fff;cursor:pointer;">
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

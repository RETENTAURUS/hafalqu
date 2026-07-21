<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanRekapKelasExport implements WithMultipleSheets
{
    public function __construct(
        private array  $data,
        private string $dateFrom,
        private string $dateTo,
        private ?object $kelas
    ) {}

    public function sheets(): array
    {
        return [
            'Ranking Siswa'    => new Sheets\RankingSheet($this->data, $this->dateFrom, $this->dateTo, $this->kelas),
            'Distribusi Nilai' => new Sheets\DistribusiSheet($this->data, $this->dateFrom, $this->dateTo, $this->kelas),
            'Aktivitas Siswa'  => new Sheets\AktivitasSheet($this->data, $this->dateFrom, $this->dateTo, $this->kelas),
            'Statistik Quiz'   => new Sheets\QuizStatsSheet($this->data, $this->dateFrom, $this->dateTo, $this->kelas),
        ];
    }
}

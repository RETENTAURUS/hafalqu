<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AktivitasSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private array  $data,
        private string $dateFrom,
        private string $dateTo,
        private ?object $kelas
    ) {}

    public function title(): string { return 'Aktivitas Siswa'; }

    public function array(): array
    {
        $a     = $this->data['aktivitas'];
        $total = $a['total_siswa'] > 0 ? $a['total_siswa'] : 1;

        return [
            ['Laporan Aktivitas Siswa — ' . ($this->kelas->nama ?? 'Semua Kelas')],
            ['Periode: ' . $this->dateFrom . ' s/d ' . $this->dateTo],
            [],
            ['Status', 'Jumlah Siswa', 'Persentase'],
            ['Aktif (sudah mengerjakan quiz)', $a['aktif'],  round($a['aktif']  / $total * 100, 1) . '%'],
            ['Belum mengerjakan quiz',          $a['belum'],  round($a['belum']  / $total * 100, 1) . '%'],
            ['Total Siswa',                     $a['total_siswa'], '100%'],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 34, 'B' => 16, 'C' => 14];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a2e']],
            ],
        ];
    }
}

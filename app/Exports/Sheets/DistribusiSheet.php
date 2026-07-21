<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DistribusiSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private array  $data,
        private string $dateFrom,
        private string $dateTo,
        private ?object $kelas
    ) {}

    public function title(): string { return 'Distribusi Nilai'; }

    public function array(): array
    {
        $d = $this->data['distribusi'];
        $total = $d['total'] > 0 ? $d['total'] : 1;

        return [
            ['Laporan Distribusi Nilai — ' . ($this->kelas->nama ?? 'Semua Kelas')],
            ['Periode: ' . $this->dateFrom . ' s/d ' . $this->dateTo],
            [],
            ['Kategori', 'Jumlah', 'Persentase'],
            ['Nilai Sempurna (100)', $d['sempurna'], round($d['sempurna'] / $total * 100, 1) . '%'],
            ['Lulus (≥ passing score)', $d['lulus'], round($d['lulus'] / $total * 100, 1) . '%'],
            ['Tidak Lulus', $d['tidak_lulus'], round($d['tidak_lulus'] / $total * 100, 1) . '%'],
            ['Total Percobaan', $d['total'], '100%'],
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 12, 'C' => 14];
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

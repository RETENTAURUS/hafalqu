<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RankingSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(
        private array  $data,
        private string $dateFrom,
        private string $dateTo,
        private ?object $kelas
    ) {}

    public function title(): string { return 'Ranking Siswa'; }

    public function headings(): array
    {
        return ['Rank', 'Nama Siswa', 'Total Poin', 'Rata-rata Skor', 'Skor Terbaik', 'Quiz Dikerjakan', 'Total Percobaan'];
    }

    public function array(): array
    {
        return $this->data['ranking']->map(fn($r) => [
            $r['rank'],
            $r['name'],
            $r['points'],
            $r['avg_score'],
            $r['best_score'],
            $r['quiz_dikerjakan'],
            $r['total_attempts'],
        ])->toArray();
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 28, 'C' => 14, 'D' => 18, 'E' => 15, 'F' => 20, 'G' => 18];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = count($this->data['ranking']) + 3;

        // Info header
        $sheet->setCellValue('A1', 'Laporan Ranking Siswa — ' . ($this->kelas->nama ?? 'Semua Kelas'));
        $sheet->setCellValue('A2', 'Periode: ' . $this->dateFrom . ' s/d ' . $this->dateTo);
        $sheet->insertNewRowBefore(1, 2);

        return [
            3 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a2e']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}

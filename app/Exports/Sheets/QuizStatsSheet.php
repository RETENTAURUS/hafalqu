<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QuizStatsSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        private array  $data,
        private string $dateFrom,
        private string $dateTo,
        private ?object $kelas
    ) {}

    public function title(): string { return 'Statistik Quiz'; }

    public function array(): array
    {
        $rows = [
            ['Laporan Statistik Quiz — ' . ($this->kelas->nama ?? 'Semua Kelas')],
            ['Periode: ' . $this->dateFrom . ' s/d ' . $this->dateTo],
            [],
            ['Judul Quiz', 'Rata-rata Skor', 'Total Percobaan', 'Passing Score', '% Lulus'],
        ];

        foreach ($this->data['quizStats'] as $q) {
            $rows[] = [
                $q['title'],
                $q['avg_score'],
                $q['total_attempts'],
                $q['passing_score'],
                $q['persen_lulus'] . '%',
            ];
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 36, 'B' => 16, 'C' => 18, 'D' => 15, 'E' => 10];
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

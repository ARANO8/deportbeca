<?php

namespace App\Exports;

use App\Models\Estadistica;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TablaPosicionesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(private int $serieId, private string $nombreSerie) {}

    public function query()
    {
        return Estadistica::query()
            ->where('serie_id', $this->serieId)
            ->with('equipo')
            ->orderByDesc('pts')
            ->orderByDesc('dg')
            ->orderByDesc('gf');
    }

    public function headings(): array
    {
        return ['#', 'Equipo', 'PJ', 'PG', 'PE', 'PP', 'GF', 'GC', 'DG', 'TA', 'TR', 'PTS'];
    }

    public function map($stat): array
    {
        static $pos = 0;
        $pos++;

        return [
            $pos,
            $stat->nombre_equipo ?: ($stat->equipo->nombre_participante ?? '—'),
            $stat->pj,
            $stat->pg,
            $stat->pe,
            $stat->pp,
            $stat->gf,
            $stat->gc,
            ($stat->dg >= 0 ? '+' : '').$stat->dg,
            $stat->tarjetas_amarillas,
            $stat->tarjetas_rojas,
            $stat->pts,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return substr($this->nombreSerie, 0, 31); // Excel max 31 chars en nombre de hoja
    }
}

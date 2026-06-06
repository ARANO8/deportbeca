<?php

namespace App\Exports;

use App\Models\Preinscripcion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PreinscripcionesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function __construct(
        private ?string $tipoEvento = null,
        private ?int $disciplinaId = null,
        private ?string $estado = null
    ) {}

    public function query()
    {
        return Preinscripcion::query()
            ->with(['disciplina', 'carrera', 'facultad', 'integrantes'])
            ->when($this->tipoEvento, fn ($q) => $q->where('tipo_evento', $this->tipoEvento))
            ->when($this->disciplinaId, fn ($q) => $q->where('disciplina_id', $this->disciplinaId))
            ->when($this->estado, fn ($q) => $q->where('estado', $this->estado))
            ->orderBy('estado')
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Código',
            'Tipo',
            'Nombre / Equipo',
            'Disciplina',
            'Carrera / Facultad',
            'Representante',
            'CI Representante',
            'Email',
            'Teléfono',
            'Integrantes',
            'Estado',
            'Fecha registro',
        ];
    }

    public function map($preinscripcion): array
    {
        return [
            $preinscripcion->codigo_inscripcion,
            ucfirst($preinscripcion->tipo_inscripcion),
            $preinscripcion->nombre_participante,
            $preinscripcion->disciplina->nombre ?? '—',
            $preinscripcion->carrera->nombre ?? $preinscripcion->facultad->nombre ?? '—',
            $preinscripcion->representante_nombre,
            $preinscripcion->representante_ci,
            $preinscripcion->representante_email,
            $preinscripcion->representante_telefono,
            $preinscripcion->cantidad_integrantes ?? 1,
            ucfirst($preinscripcion->estado),
            $preinscripcion->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Encabezado
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Pre-inscripciones';
    }
}

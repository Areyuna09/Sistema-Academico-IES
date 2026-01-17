<?php

namespace App\Exports;

use App\Models\Carrera;
use App\Models\Materia;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PlantillaMateriasExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new MateriasDatosSheet(),
            new MateriasInstruccionesSheet(),
            new CarrerasMateriasSheet(),
            new MateriasExistentesSheet(),
        ];
    }
}

class MateriasDatosSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Materias';
    }

    public function headings(): array
    {
        return [
            'nombre *',
            'carrera *',
            'anno',
            'semestre',
            'correlativas_regular',
            'correlativas_rendido',
            'correlativas_rendir',
            'resolucion',
        ];
    }

    public function array(): array
    {
        return [
            ['Anatomía I', 'Tecnicatura Superior en Enfermería', '1', '1', '', '', '', 'Res. 123/2020'],
            ['Anatomía II', 'Tecnicatura Superior en Enfermería', '1', '2', 'Anatomía I', '', 'Anatomía I', 'Res. 123/2020'],
            ['Fisiología', 'Tecnicatura Superior en Enfermería', '2', '1', 'Anatomía I:Anatomía II', 'Anatomía I', 'Anatomía I:Anatomía II', 'Res. 123/2020'],
            ['', '', '', '', '', '', '', ''],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,  // nombre
            'B' => 40,  // carrera
            'C' => 8,   // anno
            'D' => 10,  // semestre
            'E' => 30,  // correlativas_regular
            'F' => 30,  // correlativas_rendido
            'G' => 30,  // correlativas_rendir
            'H' => 15,  // resolucion
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7C3AED'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:H4')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F5F3FF'],
            ],
        ]);

        $sheet->getStyle('A1:H4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        return [];
    }
}

class MateriasInstruccionesSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Instrucciones';
    }

    public function array(): array
    {
        return [
            ['INSTRUCCIONES PARA IMPORTAR MATERIAS'],
            [''],
            ['CAMPOS OBLIGATORIOS (marcados con *)'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['nombre *', 'Nombre de la materia', 'Anatomía I'],
            ['carrera *', 'Nombre EXACTO de la carrera (ver hoja "Carreras")', 'Tecnicatura Superior en Enfermería'],
            [''],
            ['CAMPOS OPCIONALES'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['anno', 'Año de la carrera (1, 2, 3, 4)', '1'],
            ['semestre', 'Semestre (1 o 2)', '1'],
            ['correlativas_regular', 'Materias que deben estar REGULARIZADAS para cursar', 'Anatomía I:Anatomía II'],
            ['correlativas_rendido', 'Materias que deben estar APROBADAS para cursar', 'Anatomía I'],
            ['correlativas_rendir', 'Materias que deben estar APROBADAS para rendir final', 'Anatomía I:Anatomía II'],
            ['resolucion', 'Numero de resolucion', 'Res. 123/2020'],
            [''],
            ['FORMATO DE CORRELATIVAS'],
            ['- Separar multiples materias con dos puntos (:)'],
            ['- Usar el nombre EXACTO de la materia como aparece en el sistema'],
            ['- Ejemplo: "Anatomía I:Anatomía II:Bioquímica"'],
            ['- Ver hoja "Materias Existentes" para nombres exactos'],
            [''],
            ['NOTAS IMPORTANTES'],
            ['1. La primera fila debe contener los encabezados exactamente como se muestran'],
            ['2. Duplicados (misma materia + carrera) se pueden actualizar o ignorar'],
            ['3. Las correlativas se procesan DESPUES de crear las materias nuevas'],
            ['4. Elimine las filas de ejemplo antes de importar'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 60,
            'C' => 40,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '5B21B6']],
        ]);

        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '059669']],
        ]);
        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0284C7']],
        ]);
        $sheet->getStyle('A17')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'D97706']],
        ]);
        $sheet->getStyle('A23')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC2626']],
        ]);

        $sheet->getStyle('A4:C4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
        ]);
        $sheet->getStyle('A9:C9')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
        ]);

        return [];
    }
}

class CarrerasMateriasSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Carreras Disponibles';
    }

    public function headings(): array
    {
        return ['Nombre de Carrera (copiar exactamente)', 'ID'];
    }

    public function array(): array
    {
        return Carrera::orderBy('nombre')
            ->get()
            ->map(fn($c) => [$c->nombre, $c->Id])
            ->toArray();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 10,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C3AED']],
        ]);

        return [];
    }
}

class MateriasExistentesSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Materias Existentes';
    }

    public function headings(): array
    {
        return ['Nombre de Materia', 'Carrera', 'Año', 'Semestre'];
    }

    public function array(): array
    {
        return Materia::with('carrera')
            ->orderBy('carrera')
            ->orderBy('anno')
            ->orderBy('nombre')
            ->get()
            ->map(fn($m) => [
                $m->nombre,
                $m->carrera?->nombre ?? 'N/A',
                $m->anno ?? '-',
                $m->semestre ?? '-',
            ])
            ->toArray();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 45,
            'C' => 8,
            'D' => 10,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0891B2']],
        ]);

        return [];
    }
}

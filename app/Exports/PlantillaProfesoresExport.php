<?php

namespace App\Exports;

use App\Models\Carrera;
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

class PlantillaProfesoresExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ProfesoresDatosSheet(),
            new ProfesoresInstruccionesSheet(),
            new CarrerasProfesorSheet(),
        ];
    }
}

class ProfesoresDatosSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Profesores';
    }

    public function headings(): array
    {
        return [
            'dni *',
            'apellido *',
            'nombre *',
            'email',
            'carrera',
            'division',
        ];
    }

    public function array(): array
    {
        // Una sola fila de ejemplo
        return [
            ['20123456', 'Pérez', 'Carlos Alberto', 'carlos.perez@email.com', 'Tecnicatura Superior en Desarrollo de Software', '1'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 20,
            'C' => 20,
            'D' => 35,
            'E' => 45,
            'F' => 12,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:F2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECFDF5'],
            ],
        ]);

        $sheet->getStyle('A1:F2')->applyFromArray([
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

class ProfesoresInstruccionesSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Instrucciones';
    }

    public function array(): array
    {
        return [
            ['INSTRUCCIONES PARA IMPORTAR PROFESORES'],
            [''],
            ['CAMPOS OBLIGATORIOS (marcados con *)'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['dni *', 'Documento de identidad, solo numeros', '20123456'],
            ['apellido *', 'Apellido del profesor', 'Pérez'],
            ['nombre *', 'Nombre del profesor', 'Carlos Alberto'],
            [''],
            ['CAMPOS OPCIONALES'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['email', 'Correo electronico', 'carlos@email.com'],
            ['carrera', 'Nombre de la carrera principal (opcional)', 'Tecnicatura Superior en Enfermería'],
            ['division', 'Division del profesor', 'A'],
            [''],
            ['NOTAS IMPORTANTES'],
            ['1. La primera fila debe contener los encabezados exactamente como se muestran'],
            ['2. Se creara automaticamente un usuario con contraseña igual al DNI'],
            ['3. Los registros duplicados (mismo DNI) se pueden actualizar o ignorar'],
            ['4. La carrera es opcional para profesores que dictan en varias carreras'],
            ['5. Elimine las filas de ejemplo antes de importar'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 50,
            'C' => 40,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '047857']],
        ]);

        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '059669']],
        ]);
        $sheet->getStyle('A9')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0284C7']],
        ]);
        $sheet->getStyle('A14')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC2626']],
        ]);

        $sheet->getStyle('A4:C4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);
        $sheet->getStyle('A10:C10')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        return [];
    }
}

class CarrerasProfesorSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
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
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7C3AED'],
            ],
        ]);

        return [];
    }
}

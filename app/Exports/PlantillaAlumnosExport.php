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

class PlantillaAlumnosExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new AlumnosDatosSheet(),
            new AlumnosInstruccionesSheet(),
            new CarrerasDisponiblesSheet(),
        ];
    }
}

class AlumnosDatosSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Alumnos';
    }

    public function headings(): array
    {
        return [
            'dni *',
            'apellido *',
            'nombre *',
            'carrera *',
            'email',
            'telefono',
            'celular',
            'legajo',
            'anno',
            'division',
            'turno',
        ];
    }

    public function array(): array
    {
        // Una sola fila de ejemplo
        return [
            ['12345678', 'García', 'Juan Carlos', 'Tecnicatura Superior en Desarrollo de Software', 'juan.garcia@email.com', '0381-4567890', '3814567890', '', '2025', '1', ''],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // dni
            'B' => 20,  // apellido
            'C' => 20,  // nombre
            'D' => 40,  // carrera
            'E' => 30,  // email
            'F' => 15,  // telefono
            'G' => 15,  // celular
            'H' => 10,  // legajo
            'I' => 8,   // anno
            'J' => 10,  // division
            'K' => 12,  // turno
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Estilo para encabezados
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para fila de ejemplo
        $sheet->getStyle('A2:K2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFF6FF'],
            ],
        ]);

        // Bordes
        $sheet->getStyle('A1:K2')->applyFromArray([
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

class AlumnosInstruccionesSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Instrucciones';
    }

    public function array(): array
    {
        return [
            ['INSTRUCCIONES PARA IMPORTAR ALUMNOS'],
            [''],
            ['CAMPOS OBLIGATORIOS (marcados con *)'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['dni *', 'Documento de identidad, solo numeros', '12345678'],
            ['apellido *', 'Apellido del alumno', 'García'],
            ['nombre *', 'Nombre del alumno', 'Juan Carlos'],
            ['carrera *', 'Nombre EXACTO de la carrera (ver hoja "Carreras")', 'Tecnicatura Superior en Enfermería'],
            [''],
            ['CAMPOS OPCIONALES'],
            ['Campo', 'Descripcion', 'Ejemplo'],
            ['email', 'Correo electronico', 'juan@email.com'],
            ['telefono', 'Telefono fijo', '0381-4567890'],
            ['celular', 'Telefono celular', '3814567890'],
            ['legajo', 'Numero de legajo', '001'],
            ['anno', 'Año de ingreso (ej: 2024, 2025)', '2025'],
            ['division', 'Division', 'A'],
            ['turno', 'Turno (Mañana, Tarde, Noche)', 'Mañana'],
            [''],
            ['NOTAS IMPORTANTES'],
            ['1. La primera fila debe contener los encabezados exactamente como se muestran'],
            ['2. El nombre de la carrera debe coincidir EXACTAMENTE con el sistema'],
            ['3. Se creara automaticamente un usuario con contraseña igual al DNI'],
            ['4. Los registros duplicados (mismo DNI) se pueden actualizar o ignorar'],
            ['5. Elimine las filas de ejemplo antes de importar'],
            ['6. El CURSO se calcula automaticamente segun el año de ingreso'],
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
        // Título
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1E40AF']],
        ]);

        // Subtítulos
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '059669']],
        ]);
        $sheet->getStyle('A10')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '0284C7']],
        ]);
        $sheet->getStyle('A18')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC2626']],
        ]);

        // Encabezados de tabla
        $sheet->getStyle('A4:C4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);
        $sheet->getStyle('A11:C11')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        return [];
    }
}

class CarrerasDisponiblesSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths
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

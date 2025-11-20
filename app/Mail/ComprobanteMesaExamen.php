<?php

namespace App\Mail;

use App\Models\InscripcionMesa;
use App\Models\MesaExamen;
use App\Models\Alumno;
use App\Models\Configuracion;
use App\Models\Carrera;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ComprobanteMesaExamen extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;
    public $configuracion;

    /**
     * Create a new message instance.
     */
    public function __construct(Alumno $alumno, MesaExamen $mesa, InscripcionMesa $inscripcion)
    {
        // Cargar relaciones explícitamente
        $mesa->load(['materia', 'presidente', 'vocal1', 'vocal2']);

        $materiaData = $mesa->getRelation('materia');

        // Obtener carrera directamente desde el ID
        $carreraData = null;
        if ($materiaData && $materiaData->carrera) {
            $carreraData = Carrera::find($materiaData->carrera);
        }

        // Preparar datos en el mismo formato que el PDF
        $this->datos = [
            'inscripcion_id' => $inscripcion->id,
            'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
            'estado' => $inscripcion->estado,
            'mesa' => [
                'llamado' => $mesa->llamado,
                'fecha_examen' => $mesa->fecha_examen,
                'hora_examen' => $mesa->hora_examen,
                'aula' => $mesa->aula,
                'observaciones' => $mesa->observaciones,
            ],
            'materia' => [
                'nombre' => $materiaData->nombre,
            ],
            'carrera' => [
                'nombre' => $carreraData?->nombre ?? 'Sin carrera',
            ],
            'alumno' => [
                'nombre_completo' => $alumno->nombre_completo,
                'dni' => $alumno->dni,
                'email' => $alumno->email,
                'legajo' => $alumno->legajo,
            ],
            'tribunal' => [
                'presidente' => $mesa->presidente?->nombre_completo,
                'vocal1' => $mesa->vocal1?->nombre_completo,
                'vocal2' => $mesa->vocal2?->nombre_completo,
            ],
        ];

        $this->configuracion = Configuracion::get();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Inscripción a Mesa de Examen - ' . $this->datos['materia']['nombre'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.comprobante-mesa-examen',
            with: [
                'datos' => $this->datos,
                'configuracion' => $this->configuracion,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = \PDF::loadView('emails.comprobante-mesa-examen', [
            'datos' => $this->datos,
            'configuracion' => $this->configuracion,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'comprobante-mesa-examen-' . $this->datos['alumno']['dni'] . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

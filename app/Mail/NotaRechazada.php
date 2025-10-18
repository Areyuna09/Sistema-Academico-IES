<?php

namespace App\Mail;

use App\Models\NotaTemporal;
use App\Models\Configuracion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotaRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public $nota;
    public $configuracion;

    /**
     * Create a new message instance.
     */
    public function __construct(NotaTemporal $nota)
    {
        // Cargar relaciones necesarias
        $nota->load(['alumno', 'profesorMateria.materiaRelacion', 'aprobadoPor']);

        $this->nota = $nota;
        $this->configuracion = Configuracion::get();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $materia = $this->nota->profesorMateria->materiaRelacion->nombre ?? 'N/A';

        return new Envelope(
            subject: "Nota Rechazada - {$materia}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nota-rechazada',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

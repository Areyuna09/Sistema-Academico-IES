<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComprobanteInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    public $alumno;
    public $inscripciones;
    public $periodo;

    /**
     * Create a new message instance.
     */
    public function __construct($alumno, $inscripciones, $periodo)
    {
        $this->alumno = $alumno;
        $this->inscripciones = $inscripciones;
        $this->periodo = $periodo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Inscripción - ' . $this->periodo->nombre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.comprobante-inscripcion',
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

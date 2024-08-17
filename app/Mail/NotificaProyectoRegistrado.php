<?php

namespace App\Mail;

use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\Proyecto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificaProyectoRegistrado extends Mailable
{
    use Queueable, SerializesModels;
    public $proyecto; //Variable publica
    public $competencia; //Variable publica
    //public $categoria; //Variable publica

    /**
     * Create a new message instance.
     */
    public function __construct(Proyecto $proyecto, Competencia $competencia)
    {
        $this->proyecto = $proyecto;
        $this->competencia = $competencia;
        //$this->categoria = $categoria;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = 'Proyecto ' . $this->proyecto->nombre . ' registrado.';

        return new Envelope(
            subject: $asunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails/notificaProyecto',
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

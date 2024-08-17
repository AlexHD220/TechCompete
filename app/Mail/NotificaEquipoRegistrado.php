<?php

namespace App\Mail;

use App\Models\Categoria;
use App\Models\Competencia;
use App\Models\Equipo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificaEquipoRegistrado extends Mailable
{
    use Queueable, SerializesModels;
    public $equipo; //Variable publica
    public $competencia; //Variable publica
    public $categoria; //Variable publica

    /**
     * Create a new message instance.
     */
    public function __construct(Equipo $equipo, Competencia $competencia, Categoria $categoria)
    {
        $this->equipo = $equipo;
        $this->competencia = $competencia;
        $this->categoria = $categoria;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = 'Equipo ' . $this->equipo->nombre . ' registrado.';

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
            //view: 'view.name',

            markdown: 'emails/notificaEquipo',
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

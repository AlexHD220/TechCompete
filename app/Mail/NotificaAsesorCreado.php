<?php

namespace App\Mail;

use App\Models\Asesor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificaAsesorCreado extends Mailable
{
    use Queueable, SerializesModels;
    public $asesor; //Variable publica

    /**
     * Create a new message instance.
     */
    public function __construct(Asesor $asesor)
    {
        $this->asesor = $asesor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = 'Asesor ' . $this->asesor->nombre . ' creado.';

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
            
            markdown: 'emails/notificaAsesor',
            //view: 'emails/notificaAsesor',
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

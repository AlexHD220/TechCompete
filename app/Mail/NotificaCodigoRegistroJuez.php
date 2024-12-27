<?php

namespace App\Mail;

use App\Models\RegistroJuez;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificaCodigoRegistroJuez extends Mailable
{
    use Queueable, SerializesModels;
    public $registro; //Variable publica

    /**
     * Create a new message instance.
     */
    public function __construct(RegistroJuez $registro)
    {
        $this->registro = $registro;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = 'Código para registro de cuenta como Juez | TechCompete';

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
            markdown: 'emails/NotificaCodigoRegistroJuez',
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

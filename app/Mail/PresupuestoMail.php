<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PresupuestoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $filePaths; // Cambiado a plural

    /**
     * Create a new message instance.
     */
    public function __construct($data, $filePaths)
    {
        $this->data = $data;
        // Aseguramos que sea array
        $this->filePaths = is_array($filePaths) ? $filePaths : [$filePaths];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Presupuesto Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.presupuesto',
            with: ['data' => $this->data],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->filePaths as $path) {
            $fullPath = storage_path('app/' . $path);

            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as('adjunto-' . basename($path))
                    ->withMime(mime_content_type($fullPath));
            }
        }

        return $attachments;
    }
}

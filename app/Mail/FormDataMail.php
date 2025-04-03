<?php

namespace App\Mail;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormDataMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $uuid;

    /**
     * Create a new message instance.
     */
    public function __construct(public $data)
    {
        $this->uuid = Str::uuid();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelles entrées de formulaire : ' . $this->uuid,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.form-data',
            with: [
                'data' => $this->data,
                'code' => $this->data['code'] ?? null,
                'uuid' => $this->uuid,
            ]
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

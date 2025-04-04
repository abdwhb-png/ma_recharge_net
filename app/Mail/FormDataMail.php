<?php

namespace App\Mail;

use Illuminate\Support\Arr;
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
    protected $code;

    /**
     * Create a new message instance.
     */
    public function __construct(public $data)
    {
        $this->uuid = Str::uuid();
        $this->code = $this->data['code'] ?? null;
        $except = ['type', 'code', 'amount', 'inverted_code', 'is_inverted', 'code_de_recharge', 'Code de recharge'];
        $this->data = Arr::except($data, $except);
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
                'code' => $this->code,
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

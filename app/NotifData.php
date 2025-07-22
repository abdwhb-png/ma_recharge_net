<?php

namespace App;

class NotifData
{
    private string $title;
    private ?string $subject;
    private ?string $body;

    /**
     * Create a new class instance.
     */
    public function __construct(string $title, ?string $body = '', ?string $subject = '')
    {
        $this->title = $title;
        $this->body = $body;
        $this->subject = $subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSubject(): string
    {
        return $this->subject ?? 'New notification';
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getData(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'subject' => $this->subject,
        ];
    }
}

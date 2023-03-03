<?php

namespace App\Mail;

use App\Models\EndPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyStatusEndpoint extends Mailable
{
    use Queueable, SerializesModels;
    public string $messageEmail;
    public $link;
    protected $isSuccess;

    /**
     * Create a new message instance.
     */
    public function __construct(protected EndPoint $endPoint)
    {
        $url = $this->endPoint->fullUrl();
        $this->isSuccess = $this->endPoint->latestLog->isSuccess;
        $this->messageEmail = "This endpoint $url was ". ($this->isSuccess ? 'recovery' : 'down');
        $this->link = route('endpoints.logs.index', $this->endPoint->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isSuccess ? 'The service was recovery' : 'The service was down';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notify',
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

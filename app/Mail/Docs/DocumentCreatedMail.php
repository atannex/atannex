<?php

namespace App\Mail\Docs;

use App\Models\Docs\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Document $document;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Document Created: '.$this->document->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.docs.created',
            with: [
                'document' => $this->document,
            ]
        );
    }
}

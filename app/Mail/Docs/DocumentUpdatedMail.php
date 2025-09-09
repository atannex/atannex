<?php

namespace App\Mail\Docs;

use App\Models\Docs\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Document Updated: ' . $this->document->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.docs.updated',
            with: [
                'document' => $this->document,
            ],
        );
    }
}

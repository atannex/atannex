<?php

namespace App\Mail\Docs;

use App\Models\Docs\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentDeletedMail extends Mailable implements ShouldQueue
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
            subject: 'Document Deleted: '.$this->document->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.docs.deleted',
            with: [
                'document' => $this->document,
            ],
        );
    }
}

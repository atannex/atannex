<?php

namespace App\Events\Docs;

use App\Models\Docs\Document;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated
{
    use Dispatchable;
    use SerializesModels;

    public Document $document;

    /**
     * Create a new event instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
}

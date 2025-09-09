<?php

namespace App\Listeners\SendDocs;

use Illuminate\Support\Facades\Log;
use App\Events\Docs\DocumentCreated;
use App\Jobs\ProcessDocs\DocumentJob;

class DocumentNotification
{

    public function handle(DocumentCreated $event): void
    {
        Log::info('DocumentNotification listener fired for document ID: ' . $event->document->id);
        DocumentJob::dispatch($event->document);
    }
}

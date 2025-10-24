<?php

namespace App\Listeners\Docs;

use App\Jobs\Docs\DocumentJob;
use Illuminate\Support\Facades\Log;
use App\Events\Docs\DocumentCreated;

class DocumentNotification
{

    public function handle(DocumentCreated $event): void
    {
        Log::info('DocumentNotification listener fired for document ID: ' . $event->document->id);
        dispatch(new DocumentJob($event->document));
    }
}

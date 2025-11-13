<?php

namespace App\Listeners\Docs;

use App\Events\Docs\DocumentCreated;
use App\Jobs\Docs\DocumentJob;
use Illuminate\Support\Facades\Log;

class DocumentNotification
{
    public function handle(DocumentCreated $event): void
    {
        Log::info('DocumentNotification listener fired for document ID: '.$event->document->id);
        dispatch(new DocumentJob($event->document));
    }
}

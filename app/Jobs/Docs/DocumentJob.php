<?php

namespace App\Jobs\Docs;

use App\Mail\Docs\DocumentCreatedMail;
use App\Models\Docs\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DocumentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new DocumentCreatedMail($this->document));
            }
        });

        Log::info('SendPostNotificationJob executed for post ID: '.$this->document->id);
    }
}

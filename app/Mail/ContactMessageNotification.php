<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Others\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Contact $contact
    ) {}

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("New Contact Message: {$this->contact->subject}")
            ->markdown('emails.contact.notification', [
                'contact' => $this->contact,
            ]);
    }
}

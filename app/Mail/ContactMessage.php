<?php

namespace App\Mail;

use App\Models\Others\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $contactMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Contact Message')
            ->view('emails.contact')
            ->with([
                'contact' => $this->contactMessage,
            ]);
    }
}

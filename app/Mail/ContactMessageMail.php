<?php

namespace App\Mail;

use App\Models\Others\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        $mail = $this->subject('New Contact Message')
            ->view('emails.contact-message');

        if ($this->contact->attachment) {
            $mail->attach(storage_path('app/'.$this->contact->attachment));
        }

        return $mail;
    }
}

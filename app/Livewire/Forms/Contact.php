<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Events\ContactMessageCreated;
use App\Enums\Subject;
use App\Models\Others\Contact as ContactMessage;
use Illuminate\Support\Facades\Auth;

class Contact extends Component
{
    public $name;

    public $email;

    public $number;

    public $subject;

    public $message;

    public $subjects;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number' => Auth::check() ? 'nullable|string|max:20' : 'required|string|max:20',
            'subject' => 'required|in:' . implode(',', Subject::getValues()),
            'message' => 'required|string|max:2000',
        ];
    }

    public function mount(array $subjects)
    {
        $this->subjects = $subjects;

        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->number = $user->phone;
        }
    }

    public function submit()
    {
        $this->validate();

        $contact = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'number' => $this->number,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        event(new ContactMessageCreated($contact));

        session()->flash('success', 'Message sent successfully!');

        $fieldsToReset = Auth::check() ? ['subject', 'message'] : ['name', 'email', 'number', 'subject', 'message'];
        $this->reset($fieldsToReset);
    }

    public function render()
    {
        return view('livewire.forms.contact');
    }
}

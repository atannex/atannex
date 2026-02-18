<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Subject;
use App\Models\Others\Contact as ContactMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Contact extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $number = null;

    public string $subject = '';

    public string $message = '';

    public array $subjects = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'number' => [Auth::check() ? 'nullable' : 'required', 'string', 'max:20'],
            'subject' => ['required', 'in:'.implode(',', Subject::asSelectArray())],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    public function mount(array $subjects): void
    {
        $this->subjects = $subjects;

        if (Auth::check()) {
            $user = Auth::user();

            $this->fill([
                'name' => $user->name,
                'email' => $user->email,
                'number' => $user->phone,
            ]);
        }
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $contact = ContactMessage::create($validated);

        session()->flash('success', 'Your message has been sent successfully.');

        $this->reset(
            Auth::check()
                ? ['subject', 'message']
                : ['name', 'email', 'number', 'subject', 'message']
        );
    }

    public function render()
    {
        return view('livewire.forms.contact');
    }
}

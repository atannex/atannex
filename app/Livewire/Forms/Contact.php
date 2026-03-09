<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enums\Subject;
use App\Mail\ContactMessageMail;
use App\Models\Others\Contact as ContactMessage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class Contact extends Component
{
    use WithFileUploads;

    /**
     * Contact form fields.
     */
    public string $name = '';
    public string $email = '';
    public ?string $number = null;
    public string $subject = '';
    public string $message = '';

    /**
     * Uploaded attachments.
     *
     * @var TemporaryUploadedFile[]
     */
    public array $attachments = [];

    /**
     * Available subject options.
     */
    public array $subjects = [];

    /**
     * Component initialization.
     */
    public function mount(array $subjects): void
    {
        $this->subjects = $subjects;

        if (Auth::check()) {
            $this->prefillAuthenticatedUser();
        }
    }

    /**
     * Prefill form fields for authenticated users.
     */
    protected function prefillAuthenticatedUser(): void
    {
        $user = Auth::user();

        $this->fill([
            'name'   => $user->name,
            'email'  => $user->email,
            'number' => $user->phone,
        ]);
    }

    /**
     * Validation rules for the form.
     */
    protected function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'number'        => [Auth::check() ? 'nullable' : 'required', 'string', 'max:20'],
            'subject'       => ['required', 'in:' . implode(',', array_keys(Subject::asSelectArray()))],
            'message'       => ['required', 'string', 'max:2000'],
            'attachments'   => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'max:5120', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ];
    }

    /**
     * Handle contact form submission.
     */
    public function submit(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {

            $validated['attachments'] = $this->storeAttachments();

            $contactMessage = ContactMessage::create($validated);

            $this->notifyAdministrators($contactMessage);
        });

        $this->flashSuccess();
        $this->resetForm();
    }

    /**
     * Store uploaded files and return stored paths.
     *
     * @return array<int, string>
     */
    protected function storeAttachments(): array
    {
        $paths = [];

        foreach ($this->attachments as $file) {
            $paths[] = $file->store('contact-files');
        }

        return $paths;
    }

    /**
     * Notify system administrators of a new contact message.
     */
    protected function notifyAdministrators(ContactMessage $contact): void
    {
        $recipients = User::role('super admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->unique()
            ->values()
            ->toArray();

        if (! empty($recipients)) {
            Mail::to($recipients)
                ->queue(new ContactMessageMail($contact));
        }
    }

    /**
     * Flash success message.
     */
    protected function flashSuccess(): void
    {
        session()->flash(
            'success',
            __('Your message has been sent successfully.')
        );
    }

    /**
     * Reset form fields after submission.
     */
    protected function resetForm(): void
    {
        $fields = Auth::check()
            ? ['subject', 'message', 'attachments']
            : ['name', 'email', 'number', 'subject', 'message', 'attachments'];

        $this->reset($fields);
    }

    /**
     * Render Livewire view.
     */
    public function render(): View
    {
        return view('livewire.forms.contact');
    }
}

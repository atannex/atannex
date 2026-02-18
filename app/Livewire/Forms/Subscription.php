<?php

namespace App\Livewire\Forms;

use Illuminate\View\View;
use Livewire\Component;

class Subscription extends Component
{
    /**
     * Render the subscription view.
     */
    public function render(): View
    {
        return view('livewire.forms.subscription');
    }
}

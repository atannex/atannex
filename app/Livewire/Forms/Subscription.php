<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Illuminate\View\View;

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

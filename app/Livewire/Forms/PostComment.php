<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class PostComment extends Component
{

    public function render(): View
    {
        return view('livewire.forms.post-comment');
    }
}

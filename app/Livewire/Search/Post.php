<?php

namespace App\Livewire\Search;

use App\Livewire\Search\Abstracts\Searchable;
use App\Livewire\Search\Traits\HasFields;

final class Post extends Searchable
{
    use HasFields;

    /**
     * Specify the view to render.
     */
    protected function view(): string
    {
        return 'livewire.search.post';
    }
}

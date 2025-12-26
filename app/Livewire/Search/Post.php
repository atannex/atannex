<?php

namespace App\Livewire\Search;

use App\Livewire\Search\Abstracts\Searchable;
use App\Livewire\Traits\HasPostSearchFields;

final class Post extends Searchable
{
    use HasPostSearchFields;

    /**
     * Specify the view to render.
     */
    protected function view(): string
    {
        return 'livewire.search.post';
    }
}

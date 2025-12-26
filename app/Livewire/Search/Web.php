<?php

namespace App\Livewire\Search;

use App\Livewire\Search\Abstracts\Searchable;
use App\Livewire\Traits\HasPostSearchFields;

/**
 * Livewire component for global search functionality, specifically for Post models.
 */
final class Web extends Searchable
{
    use HasPostSearchFields;

    /**
     * Defines the view to be rendered for this component.
     */
    protected function view(): string
    {
        return 'livewire.search.web';
    }
}

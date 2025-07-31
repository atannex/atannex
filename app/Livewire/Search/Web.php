<?php

namespace App\Livewire\Search;

use App\Models\Posts\Post;
use App\Livewire\SearchComponent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Livewire component for global search functionality, specifically for Post models.
 */
class Web extends SearchComponent
{
    /**
     * Defines the base Eloquent query for retrieving Post models with related data.
     *
     * @return Builder
     */
    protected function baseQuery(): Builder
    {
        return Post::query()->with(['author', 'category', 'tags']);
    }

    /**
     * Defines the array of fields in the Post model that should be included in the search.
     *
     * @return array<int, string>
     */
    protected function searchableFields(): array
    {
        return ['title', 'description', 'tags.name'];
    }

    /**
     * Defines the view to be rendered for this component.
     *
     * @return string
     */
    protected function view(): string
    {
        return 'livewire.search.web';
    }
}

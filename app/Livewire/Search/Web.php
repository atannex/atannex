<?php

namespace App\Livewire\Search;

use App\Models\Posts\Post;
use App\Livewire\Search\Abstracts\Searchable;
use Illuminate\Database\Eloquent\Builder;

/**
 * Livewire component for global search functionality, specifically for Post models.
 */
final class Web extends Searchable
{
    /**
     * Defines the base Eloquent query for retrieving Post models with related data.
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
     */
    protected function view(): string
    {
        return 'livewire.search.web';
    }
}

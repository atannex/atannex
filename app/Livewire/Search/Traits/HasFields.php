<?php

namespace App\Livewire\Search\Traits;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;

trait HasFields
{
    /**
     * Defines the base Eloquent query for retrieving Post models with related data.
     */
    protected function newModelQuery(): Builder
    {
        return Post::query();
    }

    /**
     * Defines the array of fields in the Post model that should be included in the search.
     *
     * @return array<int, string>
     */
    protected function searchableFields(): array
    {
        return [
            'title',
            'description',
            'tags.name',
            'regions.name',
            'category.name',
            'author.user.name'
        ];
    }
}

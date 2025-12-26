<?php

namespace App\Livewire\Traits;

use App\Enums\Flag;
use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Builder;

/**
 * Implements Searchable query + searchable fields for Posts.
 */
trait HasPostSearchFields
{
    protected function newModelQuery(): Builder
    {
        return Post::query()
            ->published()
            ->flagged(Flag::PUBLISHED)
            ->with([
                'author.user',
                'category',
                'tags',
                'regions',
            ]);
    }

    protected function searchableFields(): array
    {
        return [
            'title',
            'description',
            'tags.name',
            'regions.name',
            'category.name',
            'author.user.name',
        ];
    }
}

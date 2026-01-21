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
    /**
     * Create a query builder for published Post models with specific relations eager-loaded.
     *
     * @return Builder A query builder for Post models with the `published` scope applied and the `author.user`, `category`, `tags`, and `regions` relationships eager-loaded.
     */
    protected function newModelQuery(): Builder
    {
        return Post::query()
            ->published()
            ->with([
                'author.user',
                'category',
                'tags',
                'region',
            ]);
    }

    protected function searchableFields(): array
    {
        return [
            'title',
            'description',
            'tags.name',
            'region.name',
            'category.name',
            'author.user.name',
        ];
    }
}

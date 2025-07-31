<?php

namespace App\Livewire\Search;

use App\Livewire\SearchComponent;
use App\Models\Posts\Post as PostModel;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Validate;

class Post extends SearchComponent
{
    #[Validate('string|max:255')]
    public string $query = '';

    /**
     * Define the base query for the post search.
     *
     * @return Builder
     */
    protected function baseQuery(): Builder
    {
        return PostModel::query()->with(['author', 'tags', 'category']);
    }

    /**
     * Specify the fields to search on.
     *
     * @return array<string>
     */
    protected function searchableFields(): array
    {
        return [
            'title',
            'description',
            'tags.name',
        ];
    }

    /**
     * Specify the view to render.
     *
     * @return string
     */
    protected function view(): string
    {
        return 'livewire.search.post';
    }
}

<?php

use Illuminate\Support\Collection;

if (! function_exists('get_posts_from_tabs')) {
    /**
     * Extract unique posts from tab configuration.
     */
    function get_posts_from_tabs(array $tabs): Collection
    {
        return collect($tabs)
            ->flatMap(fn ($tab) =>
                $tab['content']
                ?? collect($tab['entities'])
                    ->flatMap(fn ($region) => $region['posts'])
            )
            ->unique('id')
            ->values();
    }
}

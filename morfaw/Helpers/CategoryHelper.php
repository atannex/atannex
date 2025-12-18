<?php

use Illuminate\Support\Str;

if (! function_exists('displayData')) {
    /**
     * Generate category display data (label + background image)
     *
     * @param  \App\Models\Category  $category
     * @return array{label:string, bgSrc:string}
     */
    function displayData($category): array
    {
        $firstPost = $category->posts->first();
        $root = $category->getAncestors()->last() ?? $category;
        $rootName = strtolower($root->name);

        $label = in_array($rootName, ['ruler', 'rulers'], true)
            ? ($category->parent
                ? "{$category->parent->name} → {$category->name}"
                : $category->name)
            : $category->name;

        $bgSrc = $firstPost?->image
            ? asset("storage/{$firstPost->image}")
            : '';

        return compact('label', 'bgSrc');
    }
}

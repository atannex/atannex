<?php

if (! function_exists('category_display_data')) {
    /**
     * Get display data for a category (label and background image).
     *
     * @param  \App\Models\Category  $category
     * @return array{label: string, bgSrc: string}
     */
    function category_display_data($category): array
    {
        $root = $category->getAncestors()->last() ?? $category;
        $rootName = strtolower($root->name);

        $label = in_array($rootName, ['ruler', 'rulers'], true) && $category->parent
            ? "{$category->parent->name} → {$category->name}"
            : $category->name;

        $bgSrc = $category->posts->first()?->image
            ? asset('storage/' . $category->posts->first()->image)
            : '';

        return compact('label', 'bgSrc');
    }
}

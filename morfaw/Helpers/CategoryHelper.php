<?php

if (! function_exists('category_display_data')) {
    /**
     * Produce display values for a category: a human-readable label and a background image URL.
     *
     * The label is the category's name except when the root ancestor's name is "ruler" or "rulers"
     * and the category has a parent — in that case the label is formatted as "ParentName → CategoryName".
     * The `bgSrc` is the public URL for the first related post's image if present, or an empty string otherwise.
     *
     * @param  \App\Models\Category  $category  The category to derive display data from.
     * @return array{label: string, bgSrc: string} Associative array with keys `label` and `bgSrc`.
     */
    function category_display_data($category): array
    {
        $root = $category->getAncestors()->last() ?? $category;
        $rootName = strtolower($root->name);

        $label = in_array($rootName, ['ruler', 'rulers'], true) && $category->parent
            ? "{$category->parent->name} → {$category->name}"
            : $category->name;

        $bgSrc = $category->posts->first()?->image
            ? asset('storage/'.$category->posts->first()->image)
            : '';

        return compact('label', 'bgSrc');
    }
}

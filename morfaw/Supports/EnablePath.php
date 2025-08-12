<?php

namespace Morfaw\Supports;

trait EnablePath
{
    protected string $slugPathSeparator = '/';

    public function getSlugPathAttribute(): string
    {
        $slugs = [];
        $current = $this;
        while ($current !== null) {
            if (!empty($current->slug)) {
                array_unshift($slugs, $current->slug);
            }
            $current = $current->parent ?? null;
        }
        return implode($this->slugPathSeparator, $slugs);
    }
}

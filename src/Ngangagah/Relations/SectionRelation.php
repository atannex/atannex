<?php

namespace Ngangagah\Relations;

use App\Models\Pages\Page;
use App\Models\Pages\Widget;
use App\Models\Pages\Category;
use App\Models\Pivots\PageSection;
use App\Models\Pivots\WidgetSection;
use App\Models\Pivots\CategorySection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait Section
 *
 * Provides relationship definitions for section-related models, including
 * revisions, pages, and widgets associations.
 *
 * @package Ngangagah\Relations
 */
trait SectionRelation
{
    /**
     * Get the pages that this section belongs to in a many-to-many relationship.
     *
     * @return BelongsToMany<PageRelation>
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_section')
            ->using(PageSection::class)
            ->withPivot([
                'config',
                'position',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at', null)
            ->orderByPivot('position');
    }

    /**
     * Get the widgets attached to this section in a many-to-many relationship.
     *
     * @return BelongsToMany<Widget>
     */
    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(Widget::class, 'widget_sections')
            ->using(WidgetSection::class)
            ->withPivot([
                'config',
                'position',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->withTimestamps()
            ->wherePivot('deleted_at', null)
            ->orderBy('widget_sections.position');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_sections')
            ->withPivot(['config', 'position', 'is_active'])
            ->withTimestamps()
            ->using(CategorySection::class);
    }
}

<?php

namespace Ngangagah\Relations;

use App\Models\Pages\Section;
use App\Models\Pivots\PageSection;
use App\Models\Pages\Page as PagesPage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait Page
 *
 * Provides relationship definitions for page-related models, implementing
 * parent-child hierarchy and section associations.
 *
 * @package Ngangagah\Relations
 */
trait PageRelation
{
    use SoftDeletes;

    /**
     * Get the parent page relationship.
     *
     * @return BelongsTo<PagesPage, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PagesPage::class, 'parent_id');
    }

    /**
     * Get the child pages relationship.
     *
     * @return HasMany<PagesPage>
     */
    public function children(): HasMany
    {
        return $this->hasMany(PagesPage::class, 'parent_id')
            ->orderBy('position');
    }

    /**
     * Get the sections attached to this page through a many-to-many relationship.
     *
     * @return BelongsToMany<Section>
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_section')
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
            ->orderBy('page_section.position');
    }
}

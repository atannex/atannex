<?php

namespace Ngangagah\Relations;

use App\Models\Pages\Section;
use App\Models\Pivots\CategorySection;
use App\Models\Posts\Post as ModelPost;
use App\Models\Pages\Category as PagesCategory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Category
{
    /**
     * Parent category relation.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PagesCategory::class, 'parent_id');
    }

    /**
     * Children categories relation.
     */
    public function children(): HasMany
    {
        return $this->hasMany(PagesCategory::class, 'parent_id');
    }

    /**
     * Posts under this category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(ModelPost::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_sections')
            ->withPivot(['config', 'position', 'is_active'])
            ->withTimestamps()
            ->using(CategorySection::class);
    }
}

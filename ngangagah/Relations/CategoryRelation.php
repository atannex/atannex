<?php

namespace Ngangagah\Relations;

use App\Models\Posts\Post;
use App\Models\Pages\Section;
use App\Models\Pages\Category;
use App\Models\Pivots\CategorySection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CategoryRelation
{
    /**
     * Parent category relation.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Children categories relation.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Posts under this category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_sections')
            ->withPivot(['config', 'position', 'is_active'])
            ->withTimestamps()
            ->using(CategorySection::class);
    }
}

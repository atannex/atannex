<?php

namespace Atannex\Relations;

use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Models\Regions\Section;
use App\Models\Regions\Category;
use App\Models\Pivots\CategorySection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CategoryRelation
{
    /**
     * Parent category relation.
     * Each category can belong to one parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Children categories relation.
     * A category can have multiple child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('flag', Flag::PUBLISHED)
            ->with('children');
    }

    /**
     * Posts under this category.
     * Standard one-to-many relationship.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Sections associated with this category via the pivot table `category_section`.
     * Includes pivot fields `config` and `flag`.
     * Uses timestamps on the pivot table and a custom pivot model `CategorySection`.
     *
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }
}

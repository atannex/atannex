<?php

namespace Atannex\Relations;

use App\Models\Pivots\CategorySection;
use App\Models\Posts\Post;
use App\Models\Regions\Section;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CategoryRelation
{
    /**
     * Posts under this category.
     * Standard one-to-many relationship.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Sections associated with this category via the pivot table `category_section`.
     * Includes pivot fields `config` and `flag`.
     * Uses timestamps on the pivot table and a custom pivot model `CategorySection`.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'category_section')
            ->using(CategorySection::class)
            ->withPivot('flag')
            ->withTimestamps();
    }
}

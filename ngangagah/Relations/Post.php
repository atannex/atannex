<?php

namespace Ngangagah\Relations;

use App\Models\Tags\Tag;
use App\Models\Pages\Category;
use App\Models\Pivots\PostTag;
use App\Models\Regions\Employee;
use App\Models\Pivots\PostRegion;
use App\Models\Regions\Region;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Post
{
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->using(PostTag::class)
            ->withTimestamps()
            ->withPivot('id','post_id', 'tag_id');
    }
    /**
     * Post belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Post belongs to an Author (Employee).
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Post belongs to an Editor (Employee) who last updated it.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'post_region')
            ->using(PostRegion::class)
            ->withTimestamps()
            ->withPivot('deleted_at');
    }
}

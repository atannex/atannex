<?php

namespace App\Models\Pages;

use Atannex\Enables\EnableSlug;
use Atannex\Enables\EnableScope;
use Atannex\Relations\PageRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    use EnableSlug;
    use EnableScope;
    use PageRelation;

    protected string $slugSource = 'title';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug',
        'title',
        'parent_id',
        'metadata',
        'published_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get all tabs from sections and their widgets for this page.
     *
     * @return array
     */
    public function allTabs(): array
    {
        $tabs = [];

        foreach ($this->sections as $section) {
            // Section tabs
            if (!empty($section->pivot->config['tabs'])) {
                $tabs = array_merge($tabs, $section->pivot->config['tabs']);
            }

            // Widgets inside sections
            if (!empty($section->widgets)) {
                foreach ($section->widgets as $widget) {
                    if (!empty($widget->pivot->config['tabs'])) {
                        $tabs = array_merge($tabs, $widget->pivot->config['tabs']);
                    }
                }
            }
        }

        return $tabs;
    }
}

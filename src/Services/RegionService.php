<?php

declare(strict_types=1);

namespace Atannex\Services;

use App\Enums\Flag;
use App\Models\Posts\Post;
use App\Models\Regions\Category;
use App\Models\Regions\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class RegionService
{
    public function postsByRegion(Region $region, int $limit = 25): LengthAwarePaginator
    {
        return Post::query()
            ->published()
            ->whereIn('region_id', $region->getSelfAndDescendantIds())
            ->with('region')
            ->latest('published_at')
            ->paginate($limit);
    }

    public function recentPostsByRegion(Region $region, int $limit): Collection
    {
        return Post::query()
            ->published()
            ->where('region_id', $region->getKey())
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getRootCategoryRegions(): Collection
    {
        return Category::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('descendants')
            ->get();
    }

    public function getRootRegions(): Collection
    {
        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->whereNull('parent_id')
            ->with('descendants')
            ->get();
    }

    public function getRegionBySlug(string $slug): Region
    {
        $normalizedSlug = $this->normalizeSlug($slug);

        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $normalizedSlug)
            ->with([
                'sections' => static fn ($query) => $query->orderByPivot('position'),

                'sections.widgets' => static fn ($query) => $query->orderByPivot('position'),
            ])
            ->firstOrFail();
    }

    public function getRegionBySlugWithFlatWidgets(string $slug): Region
    {
        $normalizedSlug = $this->normalizeSlug($slug);

        return Region::query()
            ->flagged(Flag::PUBLISHED)
            ->where('slug_path', $normalizedSlug)
            ->with([
                'widgets' => static fn ($query) => $query
                    ->orderByPivot('position')
                    ->select('widgets.*'),
            ])
            ->firstOrFail();
    }

    private function normalizeSlug(string $slug): string
    {
        return trim($slug, '/');
    }
}

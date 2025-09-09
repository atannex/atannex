<?php

namespace Atannex\Traits;

use Atannex\Views\Traits\Normalize;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasHierarchyWithRelations
{
    use Normalize;

    /**
     * Get hierarchical items with their sub-relations.
     *
     * @param array $config
     * @return Collection
     */
    public function getHierarchyWithRelations(array $config): Collection
    {
        $modelClass           = $config['model_class'];

        $ids                  = $this->normalizeIds($config['ids']);

        $limit                = $config['limit'];
        $subLimit             = $config['sub_limit'];
        $leafSubLimit         = $config['leaf_sub_limit'];

        $childrenRelation     = $config['children_relation'];
        $subRelation          = $config['sub_relation'];

        $selectFields         = $config['select_fields'];
        $childrenSelectFields = $config['children_select_fields'];

        $sortField            = $config['sort_field'];
        $subSortField         = $config['sub_sort_field'];

        $query = $modelClass::query()
            ->select($selectFields)
            ->whereIn('id', $ids)
            ->with([
                $childrenRelation . ':' . implode(',', $childrenSelectFields),
                $childrenRelation . '.' . $subRelation => function ($query) use ($subSortField, $leafSubLimit) {
                    $query->latest($subSortField)->limit($leafSubLimit);
                },
                $subRelation => function ($query) use ($subSortField, $subLimit) {
                    $query->latest($subSortField)->limit($subLimit);
                }
            ])
            ->latest($sortField)
            ->limit($limit)
            ->get();

        return $query->map(function (Model $item) use ($subRelation, $subLimit, $leafSubLimit, $childrenRelation, $subSortField) {
            $subs = $this->resolveItemSubs($item, $subLimit, $leafSubLimit, $childrenRelation, $subRelation, $subSortField);
            $item->setRelation($subRelation, $subs);
            return $item;
        });
    }


    /**
     * Resolve sub-relations for an item, including leaf items.
     *
     * @param Model $item
     * @param int $subLimit
     * @param int $leafSubLimit
     * @param string $childrenRelation
     * @param string $subRelation
     * @param string $subSortField
     * @return Collection
     */
    private function resolveItemSubs(Model $item, int $subLimit, int $leafSubLimit, string $childrenRelation, string $subRelation, string $subSortField): Collection
    {
        if ($item->{$childrenRelation}->isEmpty()) {
            return $item->{$subRelation};
        }

        return $this->getLatestSubsFromLeafItems($item, $subLimit, $leafSubLimit, $childrenRelation, $subRelation, $subSortField);
    }

    /**
     * Collect latest sub-relations from all leaf items of an item.
     * Optimized to avoid N+1 queries and unnecessary traversals.
     *
     * @param Model $item
     * @param int $subLimit
     * @param int $leafSubLimit
     * @param string $childrenRelation
     * @param string $subRelation
     * @param string $subSortField
     * @return Collection
     */
    private function getLatestSubsFromLeafItems(Model $item, int $subLimit, int $leafSubLimit, string $childrenRelation, string $subRelation, string $subSortField): Collection
    {
        $leafItems = collect();
        $this->collectLeafItems($item, $leafItems, $childrenRelation);

        return $leafItems
            ->flatMap(function (Model $leaf) use ($subRelation, $leafSubLimit) {
                return $leaf->{$subRelation}->take($leafSubLimit);
            })
            ->sortByDesc($subSortField)
            ->take($subLimit)
            ->values();
    }

    /**
     * Recursively collect leaf items (items without children).
     * More efficient than getDescendantsAndSelf for this specific use case.
     *
     * @param Model $item
     * @param Collection $leafItems
     * @param string $childrenRelation
     * @return void
     */
    private function collectLeafItems(Model $item, Collection &$leafItems, string $childrenRelation): void
    {
        if ($item->{$childrenRelation}->isEmpty()) {
            $leafItems->push($item);
            return;
        }

        foreach ($item->{$childrenRelation} as $child) {
            $this->collectLeafItems($child, $leafItems, $childrenRelation);
        }
    }
}

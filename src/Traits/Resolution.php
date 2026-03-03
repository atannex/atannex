<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Regions\Region;

trait Resolution
{
    /**
     * In-memory cache for tab queries (prevents duplicate DB calls per request).
     */
    protected array $tabCache = [];

    /**
     * Resolve sections and widgets for a region.
     */
    protected function resolveSection(Region $region, int $sectionLimit = 6, int $widgetLimit = 3): void
    {
        $sections = $region->sections()
            ->limit($sectionLimit)
            ->with([
                'widgets' => fn($query) => $query->wherePivot('region_id', $region->id)
                    ->limit($widgetLimit),
            ])
            ->get();

        foreach ($sections as $section) {
            $this->resolveEntityWithWidgets($section);
        }

        $region->setRelation('sections', $sections);
    }

    /**
     * Resolve tabs for section and its widgets.
     */
    protected function resolveEntityWithWidgets(object $entity): void
    {
        $config = $entity->pivot->config;

        $this->resolveEntityContent($entity, $config, 'section_tab');

        foreach ($entity->widgets as $widget) {
            $widgetConfig = $widget->pivot->config;
            $this->resolveEntityContent($widget, $widgetConfig, 'widget_tab');
        }
    }

    /**
     * Attach resolved tabs to entity.
     */
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        if (empty($config[$tabKey]) || ! is_array($config[$tabKey])) {
            $entity->setRelation('tabs', collect());

            return;
        }

        $tabs = collect($config[$tabKey])
            ->map(fn(array $tab) => $this->resolveSingleTab($tab))
            ->values();

        $entity->setRelation('tabs', $tabs);
    }

    /**
     * Resolve a single tab configuration safely.
     */
    private function resolveSingleTab(array $tab): array
    {
        $mapping = $this->getMapping($tab['type']);

        if (! $mapping || empty($mapping['method'])) {
            $tab['content'] = collect();

            return $tab;
        }

        $idKey = $mapping['idKey'] ?? null;

        $tab['content'] = $this->resolveTab($tab, $mapping, $idKey);

        return $tab;
    }

    /**
     * Execute tab query with memoization (performance optimized).
     */
    private function resolveTab(array $tab, array $mapping, ?string $key = null): mixed
    {
        $params = [
            'limit' => $tab['limit'],
            'relation_limit' => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort' => $tab['sort'],
            'order' => $tab['order'],
        ];

        if ($key && isset($tab[$key])) {
            $params[$key] = normalizeIds($tab[$key]);
        }

        $method = $mapping['method'];

        if (! method_exists($this->getPost, $method)) {
            return collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Memoization Key (Prevents Duplicate Queries)
        |--------------------------------------------------------------------------
        */
        $cacheKey = md5(json_encode([$method, $params]));

        if (isset($this->tabCache[$cacheKey])) {
            return $this->tabCache[$cacheKey];
        }

        return $this->tabCache[$cacheKey] =
            $this->getPost->{$method}($params);
    }
}

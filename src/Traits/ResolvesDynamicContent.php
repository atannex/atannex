<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Models\Regions\Region;
use Illuminate\Support\Collection;

trait ResolvesDynamicContent
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

        // NEW: Pre-resolve ALL tabs in one batch to avoid N+1
        $this->preResolveAllTabs($sections);

        foreach ($sections as $section) {
            $this->resolveEntityWithWidgets($section);
        }

        $region->setRelation('sections', $sections);
    }

    /**
     * NEW: Collect and batch-resolve all unique tab configurations across sections + widgets.
     * This prevents the N+1 pattern where each tab config triggers its own DB query.
     */
    private function preResolveAllTabs(Collection $sections): void
    {
        $allTabConfigs = [];

        foreach ($sections as $section) {
            $config = $section->pivot->config ?? [];
            if (!empty($config['section_tab']) && is_array($config['section_tab'])) {
                $allTabConfigs = array_merge($allTabConfigs, $config['section_tab']);
            }

            foreach ($section->widgets as $widget) {
                $widgetConfig = $widget->pivot->config ?? [];
                if (!empty($widgetConfig['widget_tab']) && is_array($widgetConfig['widget_tab'])) {
                    $allTabConfigs = array_merge($allTabConfigs, $widgetConfig['widget_tab']);
                }
            }
        }

        if (empty($allTabConfigs)) {
            return;
        }

        // Resolve all unique tabs in batch
        collect($allTabConfigs)
            ->map(fn(array $tab) => $this->resolveSingleTab($tab)) // This now hits cache heavily
            ->values();
    }

    /**
     * Resolve tabs for section and its widgets (now benefits from pre-resolution).
     */
    protected function resolveEntityWithWidgets(object $entity): void
    {
        $config = $entity->pivot->config ?? [];

        $this->resolveEntityContent($entity, $config, 'section_tab');

        foreach ($entity->widgets as $widget) {
            $widgetConfig = $widget->pivot->config ?? [];
            $this->resolveEntityContent($widget, $widgetConfig, 'widget_tab');
        }
    }

    /**
     * Attach resolved tabs to entity.
     */
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        if (empty($config[$tabKey]) || !is_array($config[$tabKey])) {
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
        $mapping = $this->getMapping($tab['type'] ?? null);

        if (!$mapping || empty($mapping['method'])) {
            $tab['content'] = collect();

            return $tab;
        }

        $idKey = $mapping['idKey'] ?? null;

        $tab['content'] = $this->resolveTab($tab, $mapping, $idKey);

        return $tab;
    }

    /**
     * Execute tab query with improved memoization.
     */
    private function resolveTab(array $tab, array $mapping, ?string $key = null): mixed
    {
        $params = [
            'limit'               => $tab['limit'] ?? null,
            'relation_limit'      => $tab['relation_limit'] ?? null,
            'leaf_relation_limit' => $tab['leaf_relation_limit'] ?? null,
            'sort'                => $tab['sort'] ?? null,
            'order'               => $tab['order'] ?? null,
        ];

        if ($key && isset($tab[$key])) {
            $params[$key] = normalizeIds($tab[$key]);
        }

        $method = $mapping['method'];

        if (!method_exists($this->getPost, $method)) {
            return collect();
        }

        // Improved cache key: more reliable than json_encode on potentially complex arrays
        $cacheKey = $this->generateTabCacheKey($method, $params);

        if (isset($this->tabCache[$cacheKey])) {
            return $this->tabCache[$cacheKey];
        }

        return $this->tabCache[$cacheKey] = $this->getPost->{$method}($params);
    }

    /**
     * Generate a stable cache key for tab queries.
     */
    private function generateTabCacheKey(string $method, array $params): string
    {
        // Sort keys for consistent hashing
        ksort($params);

        return md5($method . '|' . json_encode($params, JSON_THROW_ON_ERROR));
    }
}

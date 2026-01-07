<?php

namespace Atannex\Traits;

use App\Models\Regions\Region;

trait Resolution
{
    // ------------------------------------------------------------
    // Resolve sections for a region along with their widgets.
    // ------------------------------------------------------------
    protected function resolveSection(Region $region, int $sectionLimit = 6, int $widgetLimit = 3): void
    {
        $sections = $region->sections()
            ->limit($sectionLimit)
            ->with([
                'widgets' => fn($q) => $q->wherePivot('region_id', $region->id)
                    ->limit($widgetLimit)
            ])
            ->get();

        $sections->each(fn($section) => $this->resolveEntityWithWidgets($section));

        $region->setRelation('sections', $sections);
    }

    // ------------------------------------------------------------
    // Resolve an entity (section or widget) along with its widgets.
    // ------------------------------------------------------------
    protected function resolveEntityWithWidgets(object $entity): void
    {
        $this->resolveEntityContent($entity, $entity->pivot->config, 'section_tab');

        foreach ($entity->widgets as $widget) {
            $this->resolveEntityContent($widget, $widget->pivot->config, 'widget_tab');
        }
    }

    // ------------------------------------------------------------
    // Resolve tab content for a given entity based on config.
    // Assumes tabs are always present.
    // ------------------------------------------------------------
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        $tabs = array_map(
            fn($tab) => $this->resolveSingleTab($tab),
            $config[$tabKey]
        );

        $entity->setRelation('tabs', $tabs);
    }

    // ------------------------------------------------------------
    // Resolve a single tab's content based on its type and configuration.
    // ------------------------------------------------------------
    private function resolveSingleTab(array $tab): array
    {
        $mapping = $this->getMapping($tab['type']);

        $tab['content'] = !empty($mapping['idKey'])
            ? $this->resolveTab($tab, $mapping, $mapping['idKey'])
            : $this->resolveTab($tab, $mapping);

        return $tab;
    }

    // ------------------------------------------------------------
    // Generic tab resolver: handles both keyed and non-keyed tabs.
    // ------------------------------------------------------------
    private function resolveTab(array $tab, array $mapping, ?string $key = null): mixed
    {
        $params = [
            'limit'               => $tab['limit'],
            'relation_limit'      => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort'                => $tab['sort'],
            'order'               => $tab['order'],
        ];

        if ($key) {
            $params[$key] = normalizeIds($tab[$key]);
        }

        return $this->getPost->{$mapping['method']}($params);
    }
}

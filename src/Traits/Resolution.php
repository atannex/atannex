<?php

namespace Atannex\Traits;

use App\Models\Regions\Region;

trait Resolution
{
    // ------------------------------------------------------------
    // Resolve sections for a region along with their widgets.
    // Fetches up to $sectionLimit sections and $widgetLimit widgets per section.
    // Sets the resolved sections as a relation on the region model.
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
    // Resolves main entity tabs and iterates over widgets to resolve theirs.
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
    // Maps each tab configuration to its resolved content and sets as 'tabs' relation.
    // Assumes $config[$tabKey] always exists.
    // ------------------------------------------------------------
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        $entity->setRelation('tabs', array_map(
            fn($tab) => $this->resolveSingleTab($tab),
            $config[$tabKey]
        ));
    }

    // ------------------------------------------------------------
    // Resolve a single tab's content based on its type and configuration.
    // Uses getMapping to determine method and id key, then fetches content.
    // ------------------------------------------------------------
    private function resolveSingleTab(array $tab): array
    {
        $mapping = $this->getMapping($tab['type']);
        $key = $mapping['idKey'];

        $tab['content'] = $this->getPost->{$mapping['method']}([
            $key                  => normalizeIds($tab[$key]),
            'limit'               => $tab['limit'],
            'relation_limit'      => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort'                => $tab['sort'],
            'order'               => $tab['order'],
        ]);

        return $tab;
    }
}

<?php

namespace Atannex\Traits;

use App\Models\Regions\Region;

trait Resolution
{
    // ------------------------------------------------------------
    // Resolve sections for a region along with their widgets.
    // Fetches up to $sectionLimit sections and $widgetLimit widgets per section.
    // Sets the resolved sections as a relation on the region model.
    /**
     * Load and attach resolved sections (and their widgets) on a region.
     *
     * Fetches up to $sectionLimit sections for the given region, eager-loads up to
     * $widgetLimit widgets per section (filtered by the region pivot), resolves each
     * section and its widgets via resolveEntityWithWidgets, and sets the resulting
     * collection as the region's 'sections' relation.
     *
     * @param Region $region The region to populate with resolved sections.
     * @param int $sectionLimit Maximum number of sections to retrieve (default 6).
     * @param int $widgetLimit Maximum number of widgets to retrieve per section (default 3).
     */
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
    /**
     * Resolve content tabs for the given entity and for each of its widgets.
     *
     * Uses the entity's pivot config to resolve the entity's tabs (using the 'section_tab' key)
     * and then resolves tabs for every related widget (using each widget's 'widget_tab' key).
     *
     * @param object $entity The entity (typically a section) whose tabs should be resolved; each related widget's tabs will also be resolved.
     */
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
    /**
     * Attach resolved tab content to an entity based on its configuration.
     *
     * Maps each tab configuration found at $config[$tabKey] through resolveSingleTab
     * and sets the resulting array as the entity's 'tabs' relation.
     *
     * @param object $entity The model or entity to receive the 'tabs' relation.
     * @param array $config Configuration array containing tab definitions.
     * @param string $tabKey Key in $config that holds the array of tab configurations.
     */
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
    /**
     * Resolve a single tab's content based on its type and configuration.
     *
     * @param array $tab Configuration for the tab. Required keys: `type`, the id key matching the mapping returned by `getMapping()` (e.g. `category_id` or `post_id`), and optional keys used for fetching content: `limit`, `relation_limit`, `leaf_relation_limit`, `sort`, `order`.
     * @return array The input `$tab` array with an added `content` key containing the resolved content for that tab.
     */
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
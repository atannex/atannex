<?php

namespace Atannex\Traits;

use App\Models\Regions\Region;

trait Resolution
{
    // ------------------------------------------------------------
    // Resolve sections for a region along with their widgets.
    /**
     * Load a region's sections and their widgets, resolve each entity, and attach them to the region.
     *
     * Fetches up to `$sectionLimit` sections for the provided `$region`, populates each section's widgets
     * (limited to `$widgetLimit` per section), resolves content for each section and its widgets, and
     * sets the region's `sections` relation to the resulting collection.
     *
     * @param Region $region The region whose sections and widgets should be loaded and resolved.
     * @param int $sectionLimit Maximum number of sections to load for the region.
     * @param int $widgetLimit Maximum number of widgets to load per section.
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
    /**
     * Resolve tabbed content for an entity and for each of its widgets using their pivot configs.
     *
     * Calls resolveEntityContent on the provided entity with the entity's pivot config and the 'section_tab' key,
     * then calls resolveEntityContent for each widget using the widget's pivot config and the 'widget_tab' key.
     *
     * @param object $entity The entity whose tabs should be resolved; expected to have a `pivot->config` and a `widgets` relation.
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
    // Assumes tabs are always present.
    /**
     * Resolve and attach tab content to an entity using the provided tab configuration.
     *
     * Maps each tab configuration found at $config[$tabKey] through resolveSingleTab and sets the
     * resulting array as the entity's 'tabs' relation.
     *
     * @param object $entity The target entity on which the 'tabs' relation will be set.
     * @param array $config Configuration array containing tab definitions.
     * @param string $tabKey Key within $config that holds the array of tab configurations.
     */
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
    /**
     * Resolves and attaches content for a single tab based on its type mapping.
     *
     * Retrieves the mapping for the tab's `type` and populates the tab's `content`
     * by calling resolveTab; if the mapping defines an `idKey` that key is passed
     * to resolveTab so the tab-specific identifier is included in the request.
     *
     * @param array $tab Tab configuration array; must include a 'type' entry and may include fields referenced by the mapping.
     * @return array The original tab array with a populated 'content' key.
     */
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
    /**
     * Resolve tab content by building request parameters from the tab configuration and invoking the mapped fetch method.
     *
     * @param array $tab Tab configuration containing keys `limit`, `relation_limit`, `leaf_relation_limit`, `sort`, `order` and optionally the values under `$key`.
     * @param array $mapping Mapping array that must include a `method` entry specifying the method to call on the post relation.
     * @param string|null $key Optional key name in `$tab` whose value will be normalized and added to the request parameters.
     * @return mixed The content returned by the mapped method call. */
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
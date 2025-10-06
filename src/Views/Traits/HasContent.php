<?php

namespace Atannex\Views\Traits;

use App\Models\Regions\Region;
use App\Enums\Traits\HasEntityMapping;

trait HasContent
{
    use CanNormalize;
    use HasEntityMapping;

    /**
     * Resolve sections for a region.
     *
     * @param Region $region
     * @return void
     */
    protected function resolveSection(Region $region): void
    {
        foreach ($region->sections as $section) {
            $this->resolveSectionEntity($section);
        }
    }

    /**
     * Resolve a section entity and its widgets.
     *
     * @param object $section
     * @return void
     */
    protected function resolveSectionEntity(object $section): void
    {
        $sectionConfig = $section->pivot->config;
        $this->resolveEntityContent($section, $sectionConfig);

        foreach ($section->widgets as $widget) {
            $this->resolveWidgetEntity($widget);
        }
    }

    /**
     * Resolve a widget entity.
     *
     * @param object $widget
     * @return void
     */
    protected function resolveWidgetEntity(object $widget): void
    {
        $this->resolveEntityContent($widget, $widget->pivot->config);
    }

    /**
     * Resolve entity content.
     *
     * @param object $entity
     * @param array|null $config
     * @return void
     */
    private function resolveEntityContent(object $entity, ?array $config): void
    {
        $entity->tabs = empty($config['tabs']) ? [] : $this->resolveTabs($config['tabs']);
    }

    /**
     * Resolves tab configurations into their associated entity data.
     *
     * This method safely handles incomplete or null configurations
     * and ensures that invalid mappings or missing fields do not
     * break the resolution process.
     *
     * @param array $tabsConfig Array of tab configuration data.
     * @return array The resolved tabs with their corresponding entities.
     */
    private function resolveTabs(array $tabsConfig): array
    {
        $component = $this->getComponent;
        $mappingsCache = [];

        foreach ($tabsConfig as &$tab) {
            // Skip invalid or incomplete tab entries
            $type = $tab['type'] ?? null;
            if (empty($type)) {
                $tab['entities'] = $tab['content'] = [];
                continue;
            }

            // Retrieve or cache the mapping (null-safe)
            $mapping = $mappingsCache[$type] ??= $this->getMapping($type);
            if (empty($mapping) || empty($mapping['method'])) {
                $tab['entities'] = $tab['content'] = [];
                continue;
            }

            $method = $mapping['method'];

            // Prepare argument list safely
            $args = $this->requiresIdKey($type) && !empty($mapping['idKey']) && isset($tab[$mapping['idKey']])
                ? [$mapping['idKey'] => $this->normalizeIds((array) $tab[$mapping['idKey']])]
                : [];

            // Merge optional numeric and sorting parameters with fallbacks
            $args['limit'] = $tab['limit'] ?? 5;
            $args['relation_limit'] = $tab['relation_limit'] ?? 5;
            $args['leaf_relation_limit'] = $tab['leaf_relation_limit'] ?? 5;
            $args['sort'] = $tab['sort'] ?? 'created_at';
            $args['order'] = $tab['order'] ?? 'desc';

            // Verify the component method exists before calling
            if (!method_exists($component, $method)) {
                $tab['entities'] = $tab['content'] = [];
                continue;
            }

            // Execute component method and safely handle return
            $entities = $component->$method($args) ?? [];

            // Apply limit constraints safely
            if (is_array($entities) && isset($args['limit']) && count($entities) > $args['limit']) {
                $entities = array_slice($entities, 0, (int) $args['limit']);
            }

            $tab['entities'] = $tab['content'] = $entities;
        }
        unset($tab);
        return $tabsConfig;
    }
}

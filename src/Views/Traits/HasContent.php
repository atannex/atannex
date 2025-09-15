<?php

namespace Atannex\Views\Traits;

use App\Models\Pages\Page;
use App\Enums\Traits\HasEntityMapping;

trait HasContent
{
    use CanNormalize;
    use HasEntityMapping;

    /**
     * Resolve sections for a page with minimal overhead.
     *
     * @param Page $page
     * @return void
     */
    protected function resolveSection(Page $page): void
    {
        if (empty($page->sections)) {
            return;
        }

        foreach ($page->sections as $section) {
            $this->resolveSectionEntity($section);
        }
    }

    /**
     * Resolve a section entity and its widgets efficiently.
     *
     * @param object $section
     * @return void
     */
    protected function resolveSectionEntity(object $section): void
    {
        $sectionConfig = $section->pivot->config ?? [];
        $this->resolveEntityContent($section, $sectionConfig);

        if (!empty($section->widgets)) {
            foreach ($section->widgets as $widget) {
                $this->resolveWidgetEntity($widget);
            }
        }
    }

    /**
     * Resolve a widget entity with minimal processing.
     *
     * @param object $widget
     * @return void
     */
    protected function resolveWidgetEntity(object $widget): void
    {
        $this->resolveEntityContent($widget, $widget->pivot->config ?? []);
    }

    /**
     * Resolve entity content with early returns for speed.
     *
     * @param object $entity
     * @param array $config
     * @return void
     */
    private function resolveEntityContent(object $entity, array $config): void
    {
        $entity->tabs = empty($config['tabs']) ? [] : $this->resolveTabs($config['tabs']);
    }

    /**
     * Resolve tabs configuration with limit handling and caching.
     *
     * @param array $tabsConfig
     * @return array
     */
    private function resolveTabs(array $tabsConfig): array
    {
        $component = $this->getComponent; // Cache component
        $mappingsCache = []; // Cache mappings

        foreach ($tabsConfig as &$tab) {
            $type = $tab['type'] ?? '';
            $mapping = $mappingsCache[$type] ??= $this->getMapping($type);

            if (empty($mapping) || empty($mapping['method']) || !method_exists($component, $mapping['method'])) {
                $tab['entities'] = $tab['content'] = [];
                continue;
            }

            $method = $mapping['method'];
            $args = $this->requiresIdKey($type)
                ? [$mapping['idKey'] => $this->normalizeIds((array) ($tab[$mapping['idKey']] ?? []))]
                : $tab;

            // Ensure limit fields are included in arguments
            $args['limit'] = $tab['limit'] ?? null;
            $args['relation_limit'] = $tab['relation_limit'] ?? null;
            $args['leaf_relation_limit'] = $tab['leaf_relation_limit'] ?? null;
            $args['sort'] = $tab['sort'] ?? null;
            $args['order'] = $tab['order'] ?? null;

            // Fetch entities with limits applied
            $entities = $component->$method($args);

            // Fallback: Apply limit in PHP if not handled by the method
            if (!empty($args['limit']) && is_array($entities) && count($entities) > $args['limit']) {
                $entities = array_slice($entities, 0, $args['limit']);
            }

            $tab['entities'] = $tab['content'] = $entities;
        }

        unset($tab); // Clean up reference
        return $tabsConfig;
    }
}

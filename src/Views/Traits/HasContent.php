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
    protected function resolveSection(?Region $region): void
    {
        if (!$region || empty($region->sections)) {
            return;
        }

        foreach ($region->sections as $section) {
            $this->resolveSectionEntity($section);
        }
    }

    /**
     * Resolve a section entity and its widgets.
     *
     * @param object|null $section
     * @return void
     */
    protected function resolveSectionEntity(?object $section): void
    {
        if (!$section || !isset($section->pivot)) {
            return;
        }

        $sectionConfig = $section->pivot->config ?? [];
        $this->resolveEntityContent($section, $sectionConfig);

        if (!empty($section->widgets)) {
            foreach ($section->widgets as $widget) {
                $this->resolveWidgetEntity($widget);
            }
        }
    }

    /**
     * Resolve a widget entity.
     *
     * @param object|null $widget
     * @return void
     */
    protected function resolveWidgetEntity(?object $widget): void
    {
        if (!$widget || !isset($widget->pivot)) {
            return;
        }

        $this->resolveEntityContent($widget, $widget->pivot->config ?? []);
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
        if (!is_object($entity)) {
            return;
        }

        $tabsConfig = $config['tabs'] ?? [];
        $entity->tabs = empty($tabsConfig) ? [] : $this->resolveTabs($tabsConfig);
    }

    /**
     * Resolve tabs configuration with null-safe, fail-soft mapping.
     *
     * @param array $tabsConfig
     * @return array
     */
    private function resolveTabs(array $tabsConfig): array
    {
        if ($tabsConfig === []) {
            return [];
        }

        $component = $this->getComponent ?? null;
        if (!$component) {
            return [];
        }

        $mappingsCache = [];

        foreach ($tabsConfig as &$tab) {
            $type = $tab['type'] ?? null;
            if (!$type) {
                $tab['entities'] = [];
                continue;
            }

            $mapping = $mappingsCache[$type] ??= $this->getMapping($type);
            if (empty($mapping)) {
                $tab['entities'] = [];
                continue;
            }

            $method = $mapping['method'] ?? null;
            if (!$method || !method_exists($component, $method)) {
                $tab['entities'] = [];
                continue;
            }

            $args = $this->requiresIdKey($type) && !empty($mapping['idKey'])
                ? [$mapping['idKey'] => $this->normalizeIds((array)($tab[$mapping['idKey']] ?? []))]
                : (array)$tab;

            $args = array_merge([
                'limit'              => $tab['limit'] ?? 10,
                'relation_limit'     => $tab['relation_limit'] ?? 10,
                'leaf_relation_limit' => $tab['leaf_relation_limit'] ?? 10,
                'sort'               => $tab['sort'] ?? 'id',
                'order'              => $tab['order'] ?? 'desc',
            ], $args);

            $entities = $component->$method($args) ?? [];

            if (is_array($entities) && count($entities) > $args['limit']) {
                $entities = array_slice($entities, 0, $args['limit']);
            }

            $tab['entities'] = $tab['content'] = $entities;
        }

        unset($tab);
        return $tabsConfig;
    }
}

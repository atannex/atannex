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
     * Resolve tabs configuration.
     *
     * @param array $tabsConfig
     * @return array
     */
    private function resolveTabs(array $tabsConfig): array
    {
        $component = $this->getComponent;
        $mappingsCache = [];

        foreach ($tabsConfig as &$tab) {
            $type = $tab['type'];
            $mapping = $mappingsCache[$type] ??= $this->getMapping($type);

            $method = $mapping['method'];
            $args = $this->requiresIdKey($type)
                ? [$mapping['idKey'] => $this->normalizeIds((array) $tab[$mapping['idKey']])]
                : $tab;

            $args['limit'] = $tab['limit'];
            $args['relation_limit'] = $tab['relation_limit'];
            $args['leaf_relation_limit'] = $tab['leaf_relation_limit'];
            $args['sort'] = $tab['sort'];
            $args['order'] = $tab['order'];

            $entities = $component->$method($args);

            if (is_array($entities) && count($entities) > $args['limit']) {
                $entities = array_slice($entities, 0, $args['limit']);
            }

            $tab['entities'] = $tab['content'] = $entities;
        }

        unset($tab);
        return $tabsConfig;
    }
}

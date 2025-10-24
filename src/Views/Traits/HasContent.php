<?php

namespace Atannex\Views\Traits;

use App\Models\Regions\Region;
use App\Enums\Traits\HasEntityMapping;

/**
 * Trait HasContent
 *
 * Dynamically resolves region, section, and widget content,
 * ensuring widgets display only in their assigned region.
 */
trait HasContent
{
    use CanNormalize;
    use HasEntityMapping;

    /**
     * Resolve all sections for a specific region.
     *
     * @param Region $region
     * @return void
     */
    protected function resolveSection(Region $region): void
    {
        foreach ($region->sections as $section) {
            $this->resolveEntityWithWidgets($section, $region);
        }
    }

    /**
     * Resolve a section and its widgets scoped to the given region.
     *
     * @param object $entity
     * @param Region $region
     * @return void
     */
    protected function resolveEntityWithWidgets(object $entity, Region $region): void
    {
        $config = $entity->pivot->config;

        $this->resolveEntityContent($entity, $config, 'section_tab');

        $regionWidgets = $entity->widgets
            ->filter(fn($widget) => $widget->pivot->region_id === $region->id)
            ->values();

        foreach ($regionWidgets as $widget) {
            $widgetConfig = $widget->pivot->config;
            $this->resolveEntityContent($widget, $widgetConfig, 'widget_tab');
        }

        $entity->setRelation('widgets', $regionWidgets);
    }

    /**
     * Resolve tab content for a given entity using its configuration.
     *
     * @param object $entity
     * @param array $config
     * @param string $tabKey
     * @return void
     */
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        $entity->tabs = $this->resolveTabs($config[$tabKey]);
    }
}

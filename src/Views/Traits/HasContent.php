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
        $region->load([
            'sections.widgets' => fn($query) => $query->wherePivot('region_id', $region->id),
        ]);

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
    protected function resolveEntityWithWidgets(object $entity): void
    {
        $config = $entity->pivot->config;

        $this->resolveEntityContent($entity, $config, 'section_tab');

        foreach ($entity->widgets as $widget) {
            $this->resolveEntityContent($widget, $widget->pivot->config, 'widget_tab');
        }
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

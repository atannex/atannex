<?php

namespace Atannex\Views\Traits;

use App\Models\Regions\Region;
use App\Enums\Traits\HasEntityMapping;

/**
 * Trait HasContent
 *
 * Efficiently resolves region, section, and widget content
 * with minimal queries and scoped limits.
 */
trait HasContent
{
    use CanNormalize;
    use HasEntityMapping;

    /**
     * Resolve all sections for a specific region.
     *
     * @param Region $region
     * @param int $sectionLimit
     * @param int $widgetLimit
     * @return void
     */
    protected function resolveSection(Region $region, int $sectionLimit = 6, int $widgetLimit = 3): void
    {
        $region->load([
            'sections' => function ($query) use ($region, $sectionLimit, $widgetLimit) {
                $query->when($sectionLimit, fn($q) => $q->limit($sectionLimit))
                    ->with([
                        'widgets' => fn($q) =>
                        $q->wherePivot('region_id', $region->id)
                            ->when($widgetLimit, fn($w) => $w->limit($widgetLimit)),
                    ]);
            },
        ]);

        foreach ($region->sections as $section) {
            $this->resolveEntityWithWidgets($section);
        }
    }

    /**
     * Resolve a section and its widgets scoped to the given region.
     *
     * @param object $entity
     * @return void
     */
    protected function resolveEntityWithWidgets(object $entity): void
    {
        $this->resolveEntityContent($entity, $entity->pivot->config, 'section_tab');

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

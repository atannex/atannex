<?php

namespace Atannex\Views\Traits;

use App\Enums\Traits\HasEntityMapping;
use App\Models\Regions\Region;

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
     */
    protected function resolveSection(Region $region, int $sectionLimit = 6, int $widgetLimit = 3): void
    {
        $sections = $region->sections()
            ->when($sectionLimit, fn($q) => $q->limit($sectionLimit))
            ->with([
                'widgets' => fn($q) => $q->wherePivot('region_id', $region->id)
                    ->when($widgetLimit, fn($w) => $w->limit($widgetLimit)),
            ])
            ->get();

        foreach ($sections as $section) {
            $this->resolveEntityWithWidgets($section);
        }

        $region->setRelation('sections', $sections);
    }

    /**
     * Resolve a section and its widgets scoped to the given region.
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
     */
    private function resolveEntityContent(object $entity, array $config, string $tabKey): void
    {
        $entity->tabs = $this->resolveTabs($config[$tabKey]);
    }
}

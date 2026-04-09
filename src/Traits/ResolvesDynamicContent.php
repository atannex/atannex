<?php

declare(strict_types=1);

namespace Atannex\Traits;

use App\Enums\Traits\HasEntityMapping;
use App\Models\Regions\Region;

trait ResolvesDynamicContent
{
    use HasEntityMapping;

    protected function resolveSection(Region $region, int $sectionLimit = 6, int $widgetLimit = 3): void
    {
        $sections = $this->fetchSections($region, $sectionLimit, $widgetLimit);

        $this->warmUpTabs($sections);

        foreach ($sections as $section) {
            $this->hydrateSection($section);
        }

        $region->setRelation('sections', $sections);
    }

    private function fetchSections(Region $region, int $sectionLimit, int $widgetLimit): array
    {
        return $region->sections()
            ->limit($sectionLimit)
            ->with([
                'widgets' => fn($query) =>
                $query->wherePivot('region_id', $region->id)
                    ->limit($widgetLimit),
            ])
            ->get()
            ->all();
    }

    /**
     * Stream all tabs (no arrays, no merges)
     */
    private function warmUpTabs(array $sections): void
    {
        foreach ($this->collectTabs($sections) as $tab) {
            $this->resolveTab($tab);
        }
    }

    /**
     * Generator-based flattening (replaces collect + array_merge)
     */
    private function collectTabs(array $sections): iterable
    {
        foreach ($sections as $section) {
            foreach ($section->pivot->config['section_tab'] as $tab) {
                yield $tab;
            }

            foreach ($section->widgets as $widget) {
                foreach ($widget->pivot->config['widget_tab'] as $tab) {
                    yield $tab;
                }
            }
        }
    }

    private function hydrateSection(object $section): void
    {
        $this->hydrateEntityTabs($section, 'section_tab');

        foreach ($section->widgets as $widget) {
            $this->hydrateEntityTabs($widget, 'widget_tab');
        }
    }

    private function hydrateEntityTabs(object $entity, string $key): void
    {
        $resolved = [];

        foreach ($entity->pivot->config[$key] as $tab) {
            $resolved[] = $this->resolveTab($tab);
        }

        $entity->setRelation('tabs', $resolved);
    }

    private function resolveTab(array $tab): array
    {
        $mapping = $this->getMapping($tab['type']);

        if (!$mapping['method']) {
            $tab['content'] = [];
            return $tab;
        }

        $tab['content'] = $this->fetchMappedContent($mapping, $tab);

        return $tab;
    }

    private function fetchMappedContent(array $mapping, array $tab): mixed
    {
        $params = [
            'limit'               => $tab['limit'],
            'relation_limit'      => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort'                => $tab['sort'],
            'order'               => $tab['order'],
        ];

        if (!empty($mapping['idKey'])) {
            $params[$mapping['idKey']] = normalizeIds($tab[$mapping['idKey']]);
        }

        return $this->getPost->{$mapping['method']}($params);
    }
}

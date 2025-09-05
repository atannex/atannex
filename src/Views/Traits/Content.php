<?php

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Pages\Page;

trait Content
{
    use Normalize;
    use Entities;

    protected function resolveSection(?Page $page): void
    {
        foreach ($page->sections as $section) {
            $this->resolveSectionContent($section);
        }
    }

    protected function resolveSectionContent(object $section): void
    {
        $this->resolveSectionEntities($section, (array) $section->pivot->config);

        foreach ($section->widgets as $widget) {
            $this->resolveSectionEntities($widget, (array) $widget->pivot->config);
        }
    }

    private function resolveSectionEntities(object $entity, array $config): void
    {
        $entity->tabs = $this->resolveTabs($config['tabs'] ?? []);
    }


    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type']);
        $ids = (array) $tab[$mapping['idKey']];

        return [
            'entity' => $mapping['entity'],
            'ids'    => $ids,
        ];
    }

    private function groupAndNormalizeTabs(array $tabs): array
    {
        $groupedTabs = [];

        foreach ($tabs as $index => $tab) {
            ['entity' => $type, 'ids' => $ids] = $this->extractEntityAndIds($tab);

            $normalizedIds = $this->normalizeIds($ids);

            $groupedTabs[$type]['ids'] = array_merge(
                $groupedTabs[$type]['ids'] ?? [],
                $normalizedIds
            );

            $groupedTabs[$type]['tabs'][$index] = $tab;
        }

        return [$tabs, $groupedTabs];
    }

    private function resolveAndUpdateTabs(array $tabs, array $groupedTabs): array
    {
        $resolvedEntities = [];

        foreach ($groupedTabs as $type => $data) {
            foreach ($data['tabs'] as $index => $tab) {
                $uniqueIds = array_unique($data['ids']);
                $resolvedEntities[$type][$index] = $this->resolveEntities(
                    $type,
                    $uniqueIds,
                    $tab
                );
            }
        }

        foreach ($groupedTabs as $type => $data) {
            foreach ($data['tabs'] as $index => $tab) {
                $limit = $tab['limit'] ?? null;

                $tabs[$index]['entities'] = $limit
                    ? $resolvedEntities[$type][$index]->take($limit)
                    : $resolvedEntities[$type][$index];

                $tabs[$index]['content'] = $this->atannex->getPostsByType($tab);
            }
        }

        return $tabs;
    }

    private function resolveTabs(array $tabs): array
    {
        [$tabs, $groupedTabs] = $this->groupAndNormalizeTabs($tabs);
        return $this->resolveAndUpdateTabs($tabs, $groupedTabs);
    }
}

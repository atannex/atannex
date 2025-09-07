<?php

namespace Atannex\Views\Traits;

use Generator;
use App\Enums\PostType;
use App\Models\Pages\Page;
use Atannex\Helpers\Cache;

trait Content
{
    use Normalize;
    use Entities;
    use Cache;

    protected function resolveSection(?Page $page): void
    {
        if (!$page instanceof Page) {
            return;
        }

        foreach ($page->sections as $section) {
            foreach ($this->yieldSectionAndWidgets($section) as $entity) {
                $this->resolveSectionEntities($entity, (array) $entity->pivot->config);
            }
        }
    }

    /**
     * Lazy generator that yields the section itself and its widgets (if any).
     */
    private function yieldSectionAndWidgets(object $section): Generator
    {
        yield $section;

        if (!empty($section->widgets)) {
            foreach ($section->widgets as $widget) {
                yield $widget;
            }
        }
    }

    private function resolveSectionEntities(object $entity, array $config): void
    {
        if (empty($config['tabs'])) {
            $entity->tabs = [];
            return;
        }

        $entity->tabs = $this->resolveTabs($config['tabs']);
    }

    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type']);

        return [
            'entity' => $mapping['entity'],
            'ids'    => $this->normalizeIds((array) $tab[$mapping['idKey']]),
        ];
    }

    /**
     * Groups tabs by entity type and collects normalized IDs.
     */
    private function groupTabsByEntity(array $tabs): array
    {
        $groupedTabs = [];

        foreach ($tabs as $index => $tab) {
            ['entity' => $type, 'ids' => $ids] = $this->extractEntityAndIds($tab);

            $groupedTabs[$type]['ids']   = array_merge($groupedTabs[$type]['ids'] ?? [], $ids);
            $groupedTabs[$type]['tabs'][$index] = $tab;
        }

        return $groupedTabs;
    }

    /**
     * Resolves entities & posts with caching to avoid duplicate DB hits.
     */
    private function resolveAndUpdateTabs(array $tabs, array $groupedTabs): array
    {
        foreach ($groupedTabs as $type => $data) {
            $uniqueIds = array_unique($data['ids']);

            foreach ($data['tabs'] as $index => $tab) {

                $resolved = $this->getCachedEntities(
                    $type,
                    $uniqueIds,
                    fn($t, $ids) =>
                    $this->resolveEntities($t, $ids)
                );

                $limit = $tab['limit'] ?? null;
                $tabs[$index]['entities'] = $limit
                    ? $resolved->take($limit)
                    : $resolved;

                $tabs[$index]['content'] = $this->getCachedPosts(
                    $tab,
                    fn($config) =>
                    $this->atannex->getPostsByType($config)
                );
            }
        }

        return $tabs;
    }

    private function resolveTabs(array $tabs): array
    {

        if ($tabs === []) {
            return [];
        }

        $groupedTabs = $this->groupTabsByEntity($tabs);
        return $this->resolveAndUpdateTabs($tabs, $groupedTabs);
    }
}

<?php

namespace Atannex\Views\Traits;

use Generator;
use App\Enums\PostType;
use App\Models\Pages\Page;

trait HasContent
{
    use CanNormalize;
    use HasEntities;

    /**
     * Resolve all sections and their widgets for a page.
     */
    protected function resolveSection(?Page $page): void
    {
        if (!$page instanceof Page) {
            return;
        }

        foreach ($page->sections as $section) {
            foreach ($this->yieldSectionAndWidgets($section) as $entity) {
                $entity->tabs = $this->resolveTabs((array) ($entity->pivot->config['tabs'] ?? []));
            }
        }
    }

    /**
     * Yield the section and its widgets (lazy).
     */
    private function yieldSectionAndWidgets(object $section): Generator
    {
        yield $section;

        foreach ($section->widgets ?? [] as $widget) {
            yield $widget;
        }
    }

    /**
     * Resolve all tabs.
     */
    private function resolveTabs(array $tabs): array
    {
        if (!$tabs) {
            return [];
        }

        // Group tabs by entity type
        $grouped = [];
        foreach ($tabs as $index => $tab) {
            ['entity' => $type, 'ids' => $ids] = $this->extractEntityAndIds($tab);

            $grouped[$type]['ids']   = array_merge($grouped[$type]['ids'] ?? [], $ids);
            $grouped[$type]['tabs'][$index] = $tab;
        }

        foreach ($grouped as $type => $data) {
            $mapping   = PostType::getEntityMapping($type);
            $uniqueIds = array_unique($data['ids']);

            foreach ($data['tabs'] as $index => $tab) {

                // Resolve entities
                $entities = empty($mapping['idKey'])
                    ? collect()
                    : $this->resolveEntities($type, $uniqueIds);

                // Apply limit if present
                if (!empty($tab['limit'])) {
                    $entities = $entities->take($tab['limit']);
                }

                $tabs[$index]['entities'] = $entities;

                // Resolve posts
                $tabs[$index]['content'] = $this->atannex->getPostsByType($tab);
            }
        }

        return $tabs;
    }

    /**
     * Extract entity type and normalized IDs from a tab.
     */
    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type'] ?? '');
        $ids     = !empty($mapping['idKey'])
            ? $this->normalizeIds((array) ($tab[$mapping['idKey']] ?? []))
            : [];

        return ['entity' => $mapping['entity'] ?? '', 'ids' => $ids];
    }
}

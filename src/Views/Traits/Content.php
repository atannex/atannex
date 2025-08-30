<?php

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Pages\Page;
use Exception;
use Illuminate\Support\Facades\Log;

trait Content
{
    use Normalize;
    use Entities;

    /**
     * Resolve content and tabs for all sections and widgets of a page.
     */
    protected function resolveSection(?Page $page): void
    {
        if (!$page || empty($page->sections)) {
            return;
        }

        foreach ($page->sections as $section) {
            $this->resolveSectionContent($section);
        }
    }

    /**
     * Resolve a single section and its child widgets recursively.
     */
    protected function resolveSectionContent(object $section): void
    {
        if (!empty($section->pivot->config) && is_array($section->pivot->config)) {
            $this->resolveSectionEntities($section, $section->pivot->config);
        }

        if (!empty($section->widgets) && is_iterable($section->widgets)) {
            foreach ($section->widgets as $widget) {
                if (!empty($widget->pivot->config) && is_array($widget->pivot->config)) {
                    $this->resolveSectionEntities($widget, $widget->pivot->config);
                }
            }
        }
    }

    /**
     * Resolve content and tabs for a single entity.
     */
    private function resolveSectionEntities(object $entity, array $config): void
    {
        if (!empty($config['tabs']) && is_array($config['tabs'])) {
            $entity->tabs = $this->resolveTabs($config['tabs']);
        }
    }

    /**
     * Extract entity type and associated IDs from a tab configuration.
     *
     * @return array{entity: string|null, ids: array<int|string>}
     */
    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type'] ?? '');
        $ids = (array) ($tab[$mapping['idKey']] ?? []);

        return [
            'entity' => $mapping['entity'],
            'ids'    => $ids,
        ];
    }

    /**
     * Resolves the content for each tab based on the configuration.
     *
     * @return array<int, array<string, mixed>>
     */
    private function resolveTabs(array $tabs): array
    {
        $groupedTabs = [];
        foreach ($tabs as $index => $tab) {
            if (empty($tab['type'])) {
                continue;
            }

            try {
                ['entity' => $type, 'ids' => $ids] = $this->extractEntityAndIds($tab);

                if ($type && $ids) {
                    $normalizedIds = $this->normalizeIds($ids);

                    $groupedTabs[$type]['ids'] = array_merge(
                        $groupedTabs[$type]['ids'] ?? [],
                        $normalizedIds
                    );

                    $groupedTabs[$type]['tabs'][$index] = $tab;
                }
            } catch (Exception $e) {

                $tabs[$index]['entities'] = [];
                $tabs[$index]['content']  = [];
            }
        }

        $resolvedEntities = [];
        foreach ($groupedTabs as $type => $data) {
            $uniqueIds = array_unique($data['ids']);

            $resolvedEntities[$type] = $this->resolveEntities(
                $type,
                $uniqueIds,
                null
            );
        }

        foreach ($groupedTabs as $type => $data) {
            foreach ($data['tabs'] as $index => $tab) {
                $limit = $tab['limit'] ?? null;

                $tabs[$index]['entities'] = $limit
                    ? $resolvedEntities[$type]->take($limit)
                    : $resolvedEntities[$type];

                $tabs[$index]['content'] = $this->atannex->getPostsByType($tab);
            }
        }

        return $tabs;
    }
}

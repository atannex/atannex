<?php

namespace Atannex\Views\Traits;

use Exception;
use App\Enums\PostType;
use App\Models\Pages\Page;
use Illuminate\Support\Facades\Log;

trait Content
{
    use Normalize;
    use Entities;

    protected function resolveSection(?Page $page): void
    {
        if (!$page || empty($page->sections)) {
            return;
        }

        foreach ($page->sections as $section) {
            $this->resolveSectionContent($section);
        }
    }

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

    private function resolveSectionEntities(object $entity, array $config): void
    {
        if (!empty($config['tabs']) && is_array($config['tabs'])) {
            $entity->tabs = $this->resolveTabs($config['tabs']);
        }
    }

    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type'] ?? '');
        $ids = (array) ($tab[$mapping['idKey']] ?? []);

        return [
            'entity' => $mapping['entity'],
            'ids'    => $ids,
        ];
    }

    private function groupAndNormalizeTabs(array $tabs): array
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
                $tabs[$index]['content'] = [];
                Log::error("Failed to extract entities for tab: " . $e->getMessage());
            }
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

<?php

namespace Atannex\Views\Traits;

use App\Enums\PostType;
use App\Models\Pages\Page;
use Exception;

trait Content
{
    use Normalize;
    use Entities;

    /**
     * Resolve content and tabs for all sections and widgets of a page.
     *
     * @param Page|null $page The page object containing sections to resolve
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
     *
     * @param object $section The section object to resolve
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
     *
     * @param object $entity The entity to resolve
     * @param array $config Configuration array for the entity
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
     * @param array<string, mixed> $tab Tab configuration
     * @return array{entity: string|null, ids: array<int|string>}
     */
    private function extractEntityAndIds(array $tab): array
    {
        $mapping = PostType::getEntityMapping($tab['type'] ?? '');
        $ids = (array) ($tab[$mapping['idKey']] ?? []);

        return [
            'entity' => $mapping['entity'],
            'ids' => $ids
        ];
    }

    /**
     * Resolves the content for each tab based on the configuration.
     *
     * @param array<int, array<string, mixed>> $tabs Array of tab configurations
     * @return array<int, array<string, mixed>> Array of tabs with resolved entities and content
     */
    private function resolveTabs(array $tabs): array
    {
        return array_map(function ($tab) {
            $tab['entities'] = [];
            $tab['content'] = [];

            if (!isset($tab['type'])) {
                return $tab;
            }

            try {
                ['entity' => $type, 'ids' => $ids] = $this->extractEntityAndIds($tab);
                if ($type && $ids) {
                    $normalizedIds = $this->normalizeIds($ids);
                    $tab['entities'] = $this->resolveEntities($type, $normalizedIds, $tab['limit'] ?? null);
                    $tab['content'] = $this->atannex->getPostsByType($tab);
                }
            } catch (Exception) {
            }

            return $tab;
        }, $tabs);
    }
}

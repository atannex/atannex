<?php

namespace Atannex\Views\Traits;

use Illuminate\Database\Eloquent\Model;

trait Content
{
    use Normalize;
    use Entities;

    /**
     * Resolve content and tabs for all sections and their widgets of a page.
     */
    protected function resolveContent(Model $page): void
    {
        if (!$page->relationLoaded('sections')) {
            $page->load('sections.widgets');
        }

        foreach ($page->sections as $section) {
            $this->resolveEntityWithPivot($section);

            foreach ($section->widgets as $widget) {
                $this->resolveEntityWithPivot($widget);
            }
        }
    }

    /**
     * Resolve content for a model that has a pivot config.
     */
    private function resolveEntityWithPivot(Model $entity): void
    {
        $config = $entity->pivot->config ?? [];

        if (empty($config)) {
            return;
        }

        $this->resolveEntityContent($entity, $config);
    }

    /**
     * Resolve content and tabs for a single entity.
     */
    private function resolveEntityContent(Model $entity, array $config): void
    {
        $entity->content = $this->atannex->getPostsByType($config);

        $entity->tabs = !empty($config['tabs'])
            ? $this->resolveTabs($config['tabs'])
            : [];
    }

    /**
     * Resolve tabs content based on configuration.
     */
    private function resolveTabs(array $tabsConfig): array
    {
        return array_map(function (array $tabConfig): array {
            [$entityType, $ids] = $this->extractEntityAndIds($tabConfig);
            $ids = $this->normalizeIds($ids);

            return [
                ...$tabConfig,
                'entities' => $this->resolveEntities($entityType, $ids, $tabConfig['limit'] ?? null),
                'content' => $this->atannex->getPostsByType($tabConfig),
            ];
        }, $tabsConfig);
    }
}

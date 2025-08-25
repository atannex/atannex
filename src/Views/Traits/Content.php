<?php

namespace Atannex\Views\Traits;

trait Content
{
    use Normalize;
    use Entities;

    /**
     * Resolve content and tabs for all sections and widgets of a page.
     */
    protected function resolveContent(object $page): void
    {
        foreach ($page->sections as $section) {
            $this->resolveEntityContent($section, $section->pivot->config ?? []);
            $this->resolveChildEntities($section->widgets);
        }
    }

    /**
     * Resolve content for multiple child entities (widgets, etc.).
     */
    private function resolveChildEntities(iterable $entities): void
    {
        foreach ($entities as $entity) {
            $this->resolveEntityContent($entity, $entity->pivot->config ?? []);
        }
    }

    /**
     * Resolve content and tabs for a single entity.
     */
    private function resolveEntityContent(object $entity, array $config): void
    {
        $entity->content = $this->atannex->getPostsByType($config);
        $entity->tabs = $this->resolveTabs($config['tabs'] ?? []);
    }

    /**
     * Resolve tabs content based on configuration.
     */
    private function resolveTabs(array $tabsConfig): array
    {
        if ($tabsConfig === []) {
            return [];
        }

        return array_map(function (array $tabConfig): array {
            [$entityType, $ids] = $this->extractEntityAndIds($tabConfig);
            $ids = $this->normalizeIds($ids);

            return [
                ...$tabConfig,
                'entities' => $this->resolveEntities($entityType, $ids, (int) ($tabConfig['limit'] ?? 0)),
                'content' => $this->atannex->getPostsByType($tabConfig),
            ];
        }, $tabsConfig);
    }
}

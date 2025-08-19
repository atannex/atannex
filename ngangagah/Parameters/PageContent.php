<?php

namespace Ngangagah\Parameters;

use App\Models\Pages\Category;
use App\Models\Regions\Region;
use Ngangagah\Parameters\Traits\Entities;
use Ngangagah\Parameters\Traits\Normalize;

trait PageContent
{
    use Normalize;
    use Entities;


    /**
     * Resolve content and tabs for all sections and widgets of a page.
     *
     * @param  object  $page
     * @return void
     */
    protected function resolveContent(object $page): void
    {
        foreach ($page->sections as $section) {
            $this->resolveEntityContent($section, $section->pivot->config ?? []);

            foreach ($section->widgets as $widget) {
                $this->resolveEntityContent($widget, $widget->pivot->config ?? []);
            }
        }
    }

    /**
     * Resolve content and tabs for a given entity (section or widget).
     *
     * @param  object  $entity
     * @param  array   $config
     * @return void
     */
    private function resolveEntityContent(object $entity, array $config): void
    {
        $entity->content = $this->extension->getPostsByType($config);
        $entity->tabs = !empty($config['tabs']) ? $this->resolveTabs($config) : [];
    }

    /**
     * Resolve tabs content based on the tabs configuration.
     *
     * @param  array  $config
     * @return array
     */
    private function resolveTabs(array $config): array
    {
        $tabsConfig = $config['tabs'] ?? [];
        if (empty($tabsConfig)) {
            return [];
        }

        $tabs = [];

        foreach ($tabsConfig as $tabConfig) {
            [$entityType, $ids] = $this->extractEntityAndIds($tabConfig);
            $ids = $this->normalizeIds($ids);

            $limit = isset($tabConfig['limit']) ? (int) $tabConfig['limit'] : null;
            $entities = $this->resolveEntities($entityType, $ids, $limit);
            $posts = $this->extension->getPostsByType($tabConfig);

            $tabs[] = array_merge($tabConfig, [
                'entities' => $entities,
                'content' => $posts,
            ]);
        }

        return $tabs;
    }

    /**
     * Extract entity type and IDs from a tab configuration.
     *
     * @param  array  $tabConfig
     * @return array{0: ?string, 1: array<int|string>}
     */
    private function extractEntityAndIds(array $tabConfig): array
    {
        $type = $tabConfig['type'] ?? null;

        if (!isset(self::ENTITY_MAPPING[$type])) {
            return [null, []];
        }

        $mapping = self::ENTITY_MAPPING[$type];
        $ids = (array) ($tabConfig[$mapping['idKey']] ?? []);

        return [$mapping['entity'], $ids];
    }

    /**
     * Resolve entities by type and IDs, with optional limit.
     *
     * @param  string|null  $type
     * @param  array|null   $ids
     * @param  int|null     $limit
     * @return \Illuminate\Support\Collection|null
     */
    private function resolveEntities(?string $type, ?array $ids = null, ?int $limit = null)
    {
        if (empty($ids) || $type === null) {
            return null;
        }

        $query = match ($type) {
            'region' => Region::with('posts')->whereIn('id', $ids)->latest('created_at'),
            'category' => Category::with('posts')->whereIn('id', $ids)->latest('created_at'),
            default => null,
        };

        if ($query === null) {
            return null;
        }

        if ($limit !== null && $limit > 0) {
            $query = $query->limit($limit);
        }

        return $query->get();
    }
}

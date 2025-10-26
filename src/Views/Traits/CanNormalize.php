<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

/**
 * Trait CanNormalize
 *
 * Provides normalization and tab resolution logic
 * for entities with deterministic, pre-validated data.
 */
trait CanNormalize
{
    /**
     * Normalize a single ID or iterable of IDs into a flat array.
     *
     * @param int|string|iterable<int|string> $ids
     * @return array<int|string>
     */
    private function normalizeIds(int|string|iterable $ids): array
    {
        return is_iterable($ids) ? iterator_to_array($ids, false) : [$ids];
    }

    /**
     * Resolve all tabs by enriching them with their respective entities.
     *
     * @param array $tabsConfig
     * @return array
     */
    private function resolveTabs(array $tabsConfig): array
    {
        $component = $this->getComponent;

        foreach ($tabsConfig as &$tab) {
            $tab = $this->resolveSingleTab($tab, $component);
        }

        unset($tab);
        return $tabsConfig;
    }

    /**
     * Resolve a single tab’s content using its mapping definition.
     *
     * @param array $tab
     * @param object $component
     * @return array
     */
    private function resolveSingleTab(array $tab, object $component): array
    {
        $mapping = $this->getMapping($tab['type']);
        $args = $this->buildTabArguments($tab, $mapping);

        $tab['entities'] = $tab['content'] = $component->{$mapping['method']}($args)
            ->take($args['limit']);

        return $tab;
    }

    /**
     * Build arguments for a tab resolver method based on its mapping.
     *
     * @param array $tab
     * @param array $mapping
     * @return array
     */
    private function buildTabArguments(array $tab, array $mapping): array
    {
        $key = $mapping['idKey'];

        return [
            $key => $this->normalizeIds($tab[$key]),
            'limit' => $tab['limit'],
            'relation_limit' => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort' => $tab['sort'],
            'order' => $tab['order'],
        ];
    }
}

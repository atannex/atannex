<?php

declare(strict_types=1);

namespace Atannex\Views\Traits;

/**
 * Trait CanNormalize
 *
 * Efficiently normalizes IDs and resolves tab data
 * with pre-validated, deterministic input.
 * Ensures minimal queries and predictable entity hydration.
 */
trait CanNormalize
{
    /**
     * Normalize any scalar or iterable of IDs into a flat array.
     *
     * @param int|string|iterable<int|string> $ids
     * @return array<int|string>
     */
    private function normalizeIds(int|string|iterable $ids): array
    {
        return is_iterable($ids) ? array_values(is_array($ids) ? $ids : iterator_to_array($ids, false)) : [$ids];
    }

    /**
     * Resolve all tabs by hydrating them with their corresponding entities.
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
     * Resolve a single tab’s data using its mapping definition.
     *
     * @param array $tab
     * @param object $component
     * @return array
     */
    private function resolveSingleTab(array $tab, object $component): array
    {
        $mapping = $this->getMapping($tab['type']);
        $args = $this->buildTabArguments($tab, $mapping);

        $entities = $component->{$mapping['method']}($args);

        $tab['entities'] = $entities->take($args['limit']);
        $tab['content']  = $tab['entities'];

        return $tab;
    }

    /**
     * Build argument array for tab resolver methods.
     *
     * @param array $tab
     * @param array $mapping
     * @return array<string, mixed>
     */
    private function buildTabArguments(array $tab, array $mapping): array
    {
        $key = $mapping['idKey'];

        return [
            $key                  => $this->normalizeIds($tab[$key]),
            'limit'               => $tab['limit'],
            'relation_limit'      => $tab['relation_limit'],
            'leaf_relation_limit' => $tab['leaf_relation_limit'],
            'sort'                => $tab['sort'],
            'order'               => $tab['order'],
        ];
    }
}

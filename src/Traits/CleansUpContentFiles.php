<?php

declare(strict_types=1);

namespace Atannex\Traits;

use Illuminate\Support\Facades\Storage;

trait CleansUpContentFiles
{
    /**
     * Define which block types contain files.
     * Supports single keys and nested arrays.
     */
    protected array $blockFileMap = [
        'image' => 'src',
        'side-by-side' => 'image',

        // Example for future extensibility:
        // 'gallery' => ['items.*.src'],
    ];

    /**
     * Extract all file paths from content blocks.
     */
    protected function extractFilePaths(array $content): array
    {
        $files = [];

        foreach ($content as $block) {
            $type = $block['type'] ?? null;

            if (! $type || empty($this->blockFileMap[$type])) {
                continue;
            }

            foreach ((array) $this->blockFileMap[$type] as $path) {
                $files = array_merge(
                    $files,
                    $this->extractByPath($block['data'] ?? [], $path)
                );
            }
        }

        return array_values(array_unique(array_filter($files)));
    }

    /**
     * Normalize nested array extraction using dot / wildcard notation.
     */
    protected function extractByPath(array $data, string $path): array
    {
        // Handle wildcard paths like items.*.src
        if (str_contains($path, '*')) {
            $segments = explode('.*.', $path);
            $parent = data_get($data, $segments[0], []);

            if (! is_array($parent)) {
                return [];
            }

            return array_filter(
                array_map(
                    fn ($item) => data_get($item, $segments[1]),
                    $parent
                )
            );
        }

        return [data_get($data, $path)];
    }

    /**
     * Delete files safely with existence checks.
     */
    protected function deleteFiles(array $files): void
    {
        foreach ($files as $file) {
            if (
                $file &&
                Storage::disk('public')->exists($file)
            ) {
                Storage::disk('public')->delete($file);
            }
        }
    }

    /**
     * Cleanup removed files during update.
     */
    protected function cleanupRemovedFiles(array $original, array $current): void
    {
        $oldFiles = $this->extractFilePaths($original);
        $newFiles = $this->extractFilePaths($current);

        $this->deleteFiles(array_diff($oldFiles, $newFiles));
    }
}

<?php

namespace Atannex\Traits;

trait Reading
{
    /**
     * Calculate the estimated reading time of the module content.
     *
     * @return int Estimated reading time in minutes.
     */
    public function readingTime(): int
    {
        $content = $this->module_content ?? [];

        if (is_string($content)) {
            $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        $extractText = function (array $items) use (&$extractText): string {
            $text = '';

            foreach ($items as $item) {
                if (is_array($item)) {
                    $text .= match (true) {
                        isset($item['value']) => ' ' . $item['value'],
                        isset($item['title']) => ' ' . $item['title'],
                        isset($item['heading']) => ' ' . $item['heading'],
                        isset($item['paragraph']) => ' ' . $item['paragraph'],
                        isset($item['quote']) => ' ' . $item['quote'],
                        default => ' ' . $extractText($item),
                    };
                }
            }

            return $text;
        };

        $allText = $extractText($content);
        $wordCount = str_word_count($allText);

        return (int) ceil($wordCount / 200);
    }
}

<?php

namespace Atannex\Traits;

trait HasReading
{
    /**
     * Calculate the estimated reading time of the module content.
     *
     * @return int Estimated reading time in minutes.
     */
    public function readingTime(): int
    {
        $extractText = function (array $items) use (&$extractText): string {
            $text = '';

            foreach ($items as $item) {
                if (is_array($item)) {
                    foreach (['value', 'title', 'heading', 'paragraph', 'quote'] as $key) {
                        if (isset($item[$key])) {
                            $text .= ' '.$item[$key];
                        }
                    }

                    $text .= $extractText($item);
                }
            }

            return $text;
        };

        $allText = $extractText($this->content);
        $wordCount = str_word_count($allText);

        return (int) ceil($wordCount / 200);
    }
}

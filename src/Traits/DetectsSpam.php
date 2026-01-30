<?php

declare(strict_types=1);

namespace Atannex\Traits;

use Illuminate\Support\Str;

trait DetectsSpam
{
    /**
     * Spam score threshold.
     */
    protected int $spamThreshold = 60;

    /**
     * Calculate a spam score based on content quality and submission velocity.
     */
    public function spamScore(string $content, int $attempts = 0): int
    {
        $score = 0;
        $normalized = Str::lower(trim($content));

        // -------------------------------------------------
        // Content-based signals
        // -------------------------------------------------

        // Very short submissions are typically low-effort
        if (Str::length($normalized) < 20) {
            $score += 20;
        }

        // Presence of links
        if (preg_match('/https?:\/\/|www\./i', $normalized)) {
            $score += 40;
        }

        // Repeated characters (e.g. "!!!!!", "goooood")
        if (preg_match('/(.)\1{5,}/', $normalized)) {
            $score += 20;
        }

        // Excessive punctuation
        if (preg_match('/[!?]{4,}/', $normalized)) {
            $score += 15;
        }

        // All-caps shouting
        if ($this->isMostlyUppercase($content)) {
            $score += 15;
        }

        // -------------------------------------------------
        // Velocity-based signals
        // -------------------------------------------------

        if ($attempts > 1) {
            $score += min($attempts * 15, 45);
        }

        return $score;
    }

    /**
     * Determine if content should be flagged as spam.
     */
    public function isSpam(string $content, int $attempts = 0): bool
    {
        return $this->spamScore($content, $attempts) >= $this->spamThreshold;
    }

    /**
     * Detect if text is mostly uppercase.
     */
    protected function isMostlyUppercase(string $content): bool
    {
        $letters = preg_replace('/[^a-zA-Z]/', '', $content);

        if (strlen($letters) < 10) {
            return false;
        }

        $uppercaseCount = preg_match_all('/[A-Z]/', $letters);

        return ($uppercaseCount / strlen($letters)) > 0.7;
    }
}

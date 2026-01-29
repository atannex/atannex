<?php

declare(strict_types=1);

namespace Atannex\Services;

use Illuminate\Support\Str;

class SpamDetector
{
    /**
     * Calculate a spam score based on content quality and submission velocity.
     */
    public function score(string $content, int $attempts = 0): int
    {
        $score = 0;
        $normalized = Str::lower(trim($content));

        // -------------------------------------------------
        // Content-based signals
        // -------------------------------------------------

        // Very short reviews are low-effort spam
        if (Str::length($normalized) < 20) {
            $score += 20;
        }

        // Excessive links
        if (preg_match('/https?:\/\/|www\./i', $normalized)) {
            $score += 40;
        }

        // Repeated characters (e.g. "goooood", "!!!!!")
        if (preg_match('/(.)\1{5,}/', $normalized)) {
            $score += 20;
        }

        // Excessive punctuation
        if (preg_match('/[!?]{4,}/', $normalized)) {
            $score += 15;
        }

        // ALL CAPS shouting
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
     * Determine if the spam score exceeds the threshold.
     */
    public function isSpam(int $score): bool
    {
        return $score >= 60;
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

        $uppercase = preg_match_all('/[A-Z]/', $letters);

        return ($uppercase / strlen($letters)) > 0.7;
    }
}

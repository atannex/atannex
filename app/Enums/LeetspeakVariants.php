<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeetspeakVariants extends Enum
{
    public const A = 'a';
    public const E = 'e';
    public const I = 'i';
    public const O = 'o';
    public const S = 's';
    public const T = 't';

    /**
     * Map of leetspeak variants.
     *
     * @var array<string, array<string>>
     */
    protected const VARIANT_MAP = [
        self::A => ['4', '@', '/\\', '^', 'ª', 'ʍ', 'α', 'λ', 'a'],
        self::E => ['3', '€', 'ë', 'Ǝ', '₤', 'є', 'ε', 'e'],
        self::I => ['1', '!', '|', 'l', '¡', 'Ι', 'ι', 'ℐ', 'i'],
        self::O => ['0', '()', 'φ', 'Ø', 'ο', 'σ', '🅾️', 'o'],
        self::S => ['5', '$', 'z', '§', 'Ϭ', 'ς', 'ש', 's'],
        self::T => ['7', '+', '†', 'τ', '|-|', '₺', 't'],
    ];

    /**
     * Get all possible leetspeak variants for each letter.
     *
     * @return array<string, array<string>>
     */
    public static function getLeetspeakValues(): array
    {
        $escaped = [];

        foreach (self::VARIANT_MAP as $key => $variants) {
            $escaped[$key] = self::escapeForRegex($variants);
        }

        return $escaped;
    }

    /**
     * Escape special characters for use in a regular expression.
     *
     * @param array<string> $variants
     * @return array<string>
     */
    private static function escapeForRegex(array $variants): array
    {
        return array_map(fn($v) => preg_quote($v, '/'), $variants);
    }
}

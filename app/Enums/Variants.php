<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Variants Enum
 *
 * Represents leetspeak variants for specific letters.
 */
final class Variants extends Enum
{
    public const A = 'a';
    public const E = 'e';
    public const I = 'i';
    public const O = 'o';
    public const S = 's';
    public const T = 't';

    /**
     * Map of leetspeak variants for each letter.
     *
     * Data is always considered valid; no runtime checks required.
     *
     * @var array<string, array<string>>
     */
    private const VARIANT_MAP = [
        self::A => ['4', '@', '/\\', '^', 'ª', 'ʍ', 'α', 'λ', 'a'],
        self::E => ['3', '€', 'ë', 'Ǝ', '₤', 'є', 'ε', 'e'],
        self::I => ['1', '!', '|', 'l', '¡', 'Ι', 'ι', 'ℐ', 'i'],
        self::O => ['0', '()', 'φ', 'Ø', 'ο', 'σ', '🅾️', 'o'],
        self::S => ['5', '$', 'z', '§', 'Ϭ', 'ς', 'ש', 's'],
        self::T => ['7', '+', '†', 'τ', '|-|', '₺', 't'],
    ];

    /**
     * Cached escaped variants for regex use.
     *
     * @var array<string, array<string>>|null
     */
    private static ?array $escaped = null;

    /**
     * Get all leetspeak variants for regex use.
     *
     * @return array<string, array<string>>
     */
    public static function getLeetspeakValues(): array
    {
        if (self::$escaped === null) {
            self::$escaped = array_map(
                fn(array $variants) => array_map(fn(string $v) => preg_quote($v, '/'), $variants),
                self::VARIANT_MAP
            );
        }

        return self::$escaped;
    }

    /**
     * Build a regex pattern for a given letter's variants.
     *
     * @param string $letter
     * @return string
     */
    public static function getRegexPattern(string $letter): string
    {
        return '['.implode('', self::getLeetspeakValues()[$letter]).']';
    }
}

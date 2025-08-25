<?php

declare(strict_types=1);

namespace App\Enums;

use InvalidArgumentException;
use BenSampo\Enum\Enum;

/**
 * Enum for leetspeak variants of specific letters.
 *
 * @method static static A()
 * @method static static E()
 * @method static static I()
 * @method static static O()
 * @method static static S()
 * @method static static T()
 */
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
     * Cached leetspeak values.
     *
     * @var array<string, array<string>>|null
     */
    private static ?array $cachedLeetspeakValues = null;

    /**
     * Get all possible leetspeak variants for each letter, escaped for regex use.
     *
     * @return array<string, array<string>>
     */
    public static function getLeetspeakValues(): array
    {
        if (self::$cachedLeetspeakValues !== null) {
            return self::$cachedLeetspeakValues;
        }

        $escaped = [];
        foreach (self::VARIANT_MAP as $key => $variants) {
            if (!self::hasValue($key)) {
                throw new InvalidArgumentException('Invalid enum value: ' . $key);
            }

            $escaped[$key] = self::escapeForRegex($variants);
        }

        self::$cachedLeetspeakValues = $escaped;
        return $escaped;
    }

    /**
     * Build a regex pattern for a given letter's variants.
     *
     * @throws InvalidArgumentException
     */
    public static function getRegexPattern(string $letter): string
    {
        $variants = self::getLeetspeakValues();
        if (!isset($variants[$letter])) {
            throw new InvalidArgumentException('No variants found for letter: ' . $letter);
        }

        return '[' . implode('', $variants[$letter]) . ']';
    }

    /**
     * Escape special characters for use in a regular expression.
     *
     * @param array<string> $variants
     * @return array<string>
     */
    private static function escapeForRegex(array $variants): array
    {
        return array_map(fn(string $v): string => preg_quote($v, '/'), $variants);
    }
}

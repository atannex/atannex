<?php

namespace App\Rules\Auth;

use App\Enums\Auth\RestrictedNames;
use App\Enums\Auth\SimpleNames;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string):PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $minLength = 10;
        $maxLength = 500;

        // Trim input to avoid accidental whitespace issues
        $value = trim($value);

        // Validate length
        $length = mb_strlen($value);
        if ($length < $minLength) {
            $fail(sprintf('The %s must be at least %d characters long.', $attribute, $minLength));

            return;
        }

        if ($length > $maxLength) {
            $fail(sprintf('The %s must not exceed %d characters.', $attribute, $maxLength));

            return;
        }

        // Validate allowed characters: only letters and spaces (Unicode-aware)
        if (! preg_match('/^[\p{L}\s]+$/u', $value)) {
            $fail(sprintf('The %s may only contain letters and spaces.', $attribute));

            return;
        }

        // Prevent 3 or more consecutive identical characters (case-insensitive)
        if (preg_match('/(.)\1{2,}/iu', $value)) {
            $fail(sprintf('The %s should not contain three or more consecutive identical characters.', $attribute));

            return;
        }

        // Check against restricted words (case-insensitive match)
        foreach (RestrictedNames::getValues() as $restrictedWord) {
            if (stripos($value, $restrictedWord) !== false) {
                $fail(sprintf('The %s contains restricted content and is not allowed.', $attribute));

                return;
            }
        }

        // Check for overly simple names (exact match, case-insensitive)
        foreach (SimpleNames::getValues() as $simpleName) {
            if (strcasecmp($value, $simpleName) === 0) {
                $fail(sprintf('The %s is too simple and cannot be used.', $attribute));

                return;
            }
        }
    }
}

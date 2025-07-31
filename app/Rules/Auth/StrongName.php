<?php

namespace App\Rules\Auth;

use Closure;
use App\Enums\Auth\SimpleNames;
use App\Enums\Auth\RestrictedNames;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
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
            $fail("The {$attribute} must be at least {$minLength} characters long.");
            return;
        }

        if ($length > $maxLength) {
            $fail("The {$attribute} must not exceed {$maxLength} characters.");
            return;
        }

        // Validate allowed characters: only letters and spaces (Unicode-aware)
        if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
            $fail("The {$attribute} may only contain letters and spaces.");
            return;
        }

        // Prevent 3 or more consecutive identical characters (case-insensitive)
        if (preg_match('/(.)\1{2,}/iu', $value)) {
            $fail("The {$attribute} should not contain three or more consecutive identical characters.");
            return;
        }

        // Check against restricted words (case-insensitive match)
        foreach (RestrictedNames::getValues() as $restrictedWord) {
            if (stripos($value, $restrictedWord) !== false) {
                $fail("The {$attribute} contains restricted content and is not allowed.");
                return;
            }
        }

        // Check for overly simple names (exact match, case-insensitive)
        foreach (SimpleNames::getValues() as $simpleName) {
            if (strcasecmp($value, $simpleName) === 0) {
                $fail("The {$attribute} is too simple and cannot be used.");
                return;
            }
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Rules\Checks;

use Closure;
use App\Enums\Checks\CommonPassword;
use App\Enums\Checks\DictionaryWord;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string):PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Minimum length of 12 characters
        if (strlen($value) < 8) {
            $fail('The :attribute must be at least 12 characters long.');

            return;
        }

        // Check if the password contains at least one lowercase letter
        if (! preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must include at least one lowercase letter.');

            return;
        }

        // Check if the password contains at least one uppercase letter
        if (! preg_match('/[A-Z]/', $value)) {
            $fail('The :attribute must include at least one uppercase letter.');

            return;
        }

        // Check if the password contains at least one digit
        if (! preg_match('/\d/', $value)) {
            $fail('The :attribute must include at least one number.');

            return;
        }

        // Check if the password contains at least one special character
        if (! preg_match('/[@$!%*?&#]/', $value)) {
            $fail('The :attribute must include at least one special character.');

            return;
        }

        // Check if the password contains any spaces
        if (preg_match('/\s/', $value)) {
            $fail('The :attribute must not contain any spaces.');

            return;
        }

        // Check against a list of common passwords (example with a static array)
        if (CommonPassword::hasValue(strtolower($value))) {
            $fail('The :attribute is too common. Please choose a more secure password.');

            return;
        }

        // Disallow repeated characters (e.g., "aaaa", "1111")
        if (preg_match('/(.)\\1{2,}/', $value)) {
            $fail('The :attribute must not contain sequences of repeated characters.');

            return;
        }

        // Disallow sequences of increasing or decreasing characters (e.g., "abcd", "1234")
        if (preg_match('/(?:abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|123|234|345|456|567|678|789|890|901)/i', $value)) {
            $fail('The :attribute must not contain sequential characters.');

            return;
        }

        // Check if the password contains any dictionary words
        foreach (DictionaryWord::getValues() as $word) {
            if (stripos($value, $word) !== false) {
                $fail('The :attribute must not contain dictionary words.');

                return;
            }
        }
    }
}

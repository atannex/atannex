<?php

namespace App\Rules;

use Closure;
use App\Enums\Variants;
use App\Enums\Auth\RestrictedNames;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongContent implements ValidationRule
{
    /**
     * Validate the content based on various criteria.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalizedValue = mb_strtolower($value);

        if ($this->containsRestrictedContent($normalizedValue, $value)) {
            $this->logViolation($attribute, $value, 'bad language');
            $fail(__('validation.custom.' . $attribute . '.inappropriate_language'));
            return;
        }

        if ($this->hasExcessiveUppercase($value)) {
            $this->logViolation($attribute, $value, 'excessive uppercase');
            $fail(__('validation.custom.' . $attribute . '.excessive_uppercase'));
            return;
        }

        if ($this->containsRepeatedCharacters($value)) {
            $this->logViolation($attribute, $value, 'repeated characters');
            $fail(__('validation.custom.' . $attribute . '.repeated_characters'));
            return;
        }

        if ($this->containsExcessivePunctuation($value)) {
            $this->logViolation($attribute, $value, 'excessive punctuation');
            $fail(__('validation.custom.' . $attribute . '.excessive_punctuation'));
            return;
        }

        if (strlen($value) < 10) {
            $this->logViolation($attribute, $value, 'too short content');
            $fail(__('validation.custom.' . $attribute . '.too_short'));
            return;
        }

        if ($this->containsHtmlOrScripts($value)) {
            $this->logViolation($attribute, $value, 'html or script content');
            $fail(__('validation.custom.' . $attribute . '.html_or_script'));
            return;
        }

        if ($this->containsUnintelligibleContent($value)) {
            $this->logViolation($attribute, $value, 'unintelligible content');
            $fail(__('validation.custom.' . $attribute . '.unintelligible_content'));
        }
    }

    /**
     * Check if the content contains restricted words or their leetspeak variants.
     */
    protected function containsRestrictedContent(string $normalizedValue, string $originalValue): bool
    {
        foreach (RestrictedNames::getValues() as $badWord) {
            if (stripos($normalizedValue, $badWord) !== false || $this->containsLeetspeak($originalValue, $badWord)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the content contains leetspeak obfuscation of a bad word.
     */
    protected function containsLeetspeak(string $value, string $badWord): bool
    {
        $pattern = '';
        foreach (str_split($badWord) as $char) {
            $escapedChar = preg_quote($char, '/');
            $leetVariants = Variants::hasValue($char)
                ? implode('', Variants::getLeetspeakValues()[$char])
                : $escapedChar;
            $pattern .= sprintf('[%s]', $leetVariants);
        }

        return preg_match(sprintf('/%s/i', $pattern), $value) > 0;
    }



    /**
     * Check if the content has excessive uppercase characters.
     */
    protected function hasExcessiveUppercase(string $value): bool
    {
        $uppercaseContent = preg_replace('/[^A-Z]/', '', $value);
        return strlen($uppercaseContent) / strlen($value) > 0.7;
    }

    /**
     * Check if the content contains repeated characters.
     */
    protected function containsRepeatedCharacters(string $value): bool
    {
        return preg_match('/(.)\\1{3,}/', $value) > 0;
    }

    /**
     * Check if the content contains excessive punctuation.
     */
    protected function containsExcessivePunctuation(string $value): bool
    {
        return preg_match('/[!?]{3,}/', $value) > 0;
    }

    /**
     * Check if the content contains HTML tags or scripts.
     */
    protected function containsHtmlOrScripts(string $value): bool
    {
        return preg_match('/<[^>]*script.*>|<[^>]*>|<\/[^>]*>/', $value) > 0;
    }

    /**
     * Check if the content contains unintelligible content.
     */
    protected function containsUnintelligibleContent(string $value): bool
    {
        return preg_match('/lorem ipsum/i', $value) > 0;
    }

    /**
     * Log the detected content violation.
     */
    protected function logViolation(string $attribute, string $value, string $reason): void
    {
        Log::warning(sprintf("Content violation detected on '%s' with reason: %s", $attribute, $reason), [
            'content' => $value,
            'reason' => $reason,
            'timestamp' => now(),
        ]);
    }
}

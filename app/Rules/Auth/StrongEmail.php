<?php

namespace App\Rules\Auth;

use Closure;
use App\Enums\Auth\AllowedDomain;
use App\Enums\Auth\RestrictedDomain;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongEmail implements ValidationRule
{
    // Arrays to hold the allowed and restricted domains for validation.
    protected array $allowedDomains;
    protected array $restrictedDomains;

    // Custom error message for invalid emails.
    protected string $message;

    /**
     * Constructor to initialize allowed and restricted domains,
     * and set a custom or default error message.
     */
    public function __construct(string $message = '')
    {
        $this->allowedDomains = $this->getAllowedDomains();
        $this->restrictedDomains = $this->getRestrictedDomains();
        $this->message = $message ?: $this->generateDefaultMessage();
    }

    /**
     * Generates a default error message when validation fails.
     *
     * @return string
     */
    protected function generateDefaultMessage(): string
    {
        $allowedDomainsString = implode(', ', $this->allowedDomains);
        return "The :attribute must be an email address that ends with one of the following domains: $allowedDomainsString. Administrative emails are restricted to specific domains.";
    }

    /**
     * Retrieves the allowed domains from the AllowedDomain enum.
     *
     * @return array
     */
    protected function getAllowedDomains(): array
    {
        return AllowedDomain::getValues();
    }

    /**
     * Retrieves the restricted domains from the RestrictedDomain enum.
     *
     * @return array
     */
    protected function getRestrictedDomains(): array
    {
        return RestrictedDomain::getValues();
    }

    /**
     * Validates the email based on various domain rules.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Split email into local part and domain.
        $emailParts = explode('@', strtolower($value));

        // Ensure email has exactly one '@' symbol.
        if (count($emailParts) !== 2) {
            $fail("The $attribute must be a valid email address.");
            return;
        }

        [$localPart, $domain] = $emailParts;

        // Validate domain against restricted, allowed, and admin rules.
        if ($this->isDomainInvalid($domain, $fail)) {
            return;
        }

        // Ensure the local part of the email is of a minimum length.
        if (strlen($localPart) < 3) {
            $fail("The local part of the $attribute must be at least 3 characters long.");
        }
    }

    /**
     * Check if a domain is restricted, not allowed, or admin-only,
     * and fail validation if any condition is met.
     *
     * @param  string  $domain
     * @param  Closure  $fail
     * @return bool
     */
    private function isDomainInvalid(string $domain, Closure $fail): bool
    {
        if ($this->isRestrictedDomain($domain)) {
            $fail("Emails from $domain are not allowed.");
            return true;
        }

        if (!$this->isAllowedDomain($domain)) {
            $fail($this->message);
            return true;
        }

        if ($this->isAdminDomain($domain)) {
            $fail("Emails ending with $domain are for administrative use only.");
            return true;
        }

        return false;
    }

    /**
     * Checks if the domain is in the restricted domains list.
     *
     * @param  string  $domain
     * @return bool
     */
    private function isRestrictedDomain(string $domain): bool
    {
        return in_array($domain, $this->restrictedDomains);
    }

    /**
     * Checks if the domain is in the allowed domains list.
     *
     * @param  string  $domain
     * @return bool
     */
    private function isAllowedDomain(string $domain): bool
    {
        return in_array($domain, $this->allowedDomains);
    }

    /**
     * Checks if the domain is intended for administrative use only.
     *
     * @param  string  $domain
     * @return bool
     */
    private function isAdminDomain(string $domain): bool
    {
        return in_array($domain, ['atannex.com']);
    }
}

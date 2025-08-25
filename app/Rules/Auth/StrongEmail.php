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
     */
    protected function generateDefaultMessage(): string
    {
        $allowedDomainsString = implode(', ', $this->allowedDomains);
        return sprintf('The :attribute must be an email address that ends with one of the following domains: %s. Administrative emails are restricted to specific domains.', $allowedDomainsString);
    }

    /**
     * Retrieves the allowed domains from the AllowedDomain enum.
     */
    protected function getAllowedDomains(): array
    {
        return AllowedDomain::getValues();
    }

    /**
     * Retrieves the restricted domains from the RestrictedDomain enum.
     */
    protected function getRestrictedDomains(): array
    {
        return RestrictedDomain::getValues();
    }

    /**
     * Validates the email based on various domain rules.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Split email into local part and domain.
        $emailParts = explode('@', strtolower($value));

        // Ensure email has exactly one '@' symbol.
        if (count($emailParts) !== 2) {
            $fail(sprintf('The %s must be a valid email address.', $attribute));
            return;
        }

        [$localPart, $domain] = $emailParts;

        // Validate domain against restricted, allowed, and admin rules.
        if ($this->isDomainInvalid($domain, $fail)) {
            return;
        }

        // Ensure the local part of the email is of a minimum length.
        if (strlen($localPart) < 3) {
            $fail(sprintf('The local part of the %s must be at least 3 characters long.', $attribute));
        }
    }

    /**
     * Check if a domain is restricted, not allowed, or admin-only,
     * and fail validation if any condition is met.
     */
    private function isDomainInvalid(string $domain, Closure $fail): bool
    {
        if ($this->isRestrictedDomain($domain)) {
            $fail(sprintf('Emails from %s are not allowed.', $domain));
            return true;
        }

        if (!$this->isAllowedDomain($domain)) {
            $fail($this->message);
            return true;
        }

        if ($this->isAdminDomain($domain)) {
            $fail(sprintf('Emails ending with %s are for administrative use only.', $domain));
            return true;
        }

        return false;
    }

    /**
     * Checks if the domain is in the restricted domains list.
     */
    private function isRestrictedDomain(string $domain): bool
    {
        return in_array($domain, $this->restrictedDomains);
    }

    /**
     * Checks if the domain is in the allowed domains list.
     */
    private function isAllowedDomain(string $domain): bool
    {
        return in_array($domain, $this->allowedDomains);
    }

    /**
     * Checks if the domain is intended for administrative use only.
     */
    private function isAdminDomain(string $domain): bool
    {
        return $domain === 'atannex.com';
    }
}

<?php

namespace App\Rules;

use App\Models\BlacklistedDomain;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateDownloadUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if URL is valid
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('The :attribute must be a valid URL.');
            return;
        }

        // Parse URL to get domain
        $parsedUrl = parse_url($value);
        
        if (!isset($parsedUrl['host'])) {
            $fail('The :attribute must contain a valid domain.');
            return;
        }

        $domain = $parsedUrl['host'];

        // Check if domain is blacklisted
        if (BlacklistedDomain::isBlacklisted($domain)) {
            $fail('The :attribute domain is not allowed.');
            return;
        }

        // Prefer HTTPS
        if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] !== 'https') {
            // This is just a warning in production, but we'll allow HTTP for now
            // In production, you might want to enforce HTTPS only
        }
    }
}

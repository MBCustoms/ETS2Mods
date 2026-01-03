<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedDomain extends Model
{
    protected $fillable = [
        'domain',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Only active blacklisted domains
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a domain is blacklisted
     */
    public static function isBlacklisted(string $domain): bool
    {
        return static::active()
            ->where('domain', $domain)
            ->exists();
    }
}

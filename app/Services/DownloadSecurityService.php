<?php

namespace App\Services;

use App\Models\DownloadLog;
use App\Models\Mod;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DownloadSecurityService
{
    /**
     * Record a download and check for spam/abuse.
     * Returns true if download should be counted, false if blocked/ignored.
     */
    public function recordDownload(Mod $mod, string $ip, ?User $user = null): bool
    {
        // 1. Rate Limiting (Block excessive downloads from IP)
        if ($this->isRateLimited($ip)) {
            return false;
        }

        // 2. Spam Detection (Prevent duplicate counts for same mod from same IP within window)
        // Window: 1 hour
        $existingLog = DownloadLog::where('mod_id', $mod->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        // Always log the request for audit, but maybe mark as 'duplicate' or just don't increment mod counter?
        // Requirement: "Prevent downloads_count increment". 
        // We will log it regardless for security analysis, but return false to controller to skip increment.
        
        DownloadLog::create([
            'mod_id' => $mod->id,
            'user_id' => $user?->id,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
        ]);

        if ($existingLog) {
            return false; // Don't increment public counter
        }

        $this->incrementRateLimit($ip);

        return true;
    }

    protected function isRateLimited(string $ip): bool
    {
        // Limit: 50 downloads per hour
        $key = "download_limit:{$ip}";
        return Cache::get($key, 0) >= 50;
    }

    protected function incrementRateLimit(string $ip): void
    {
        $key = "download_limit:{$ip}";
        if (Cache::has($key)) {
            Cache::increment($key);
        } else {
            Cache::put($key, 1, now()->addHour());
        }
    }
}

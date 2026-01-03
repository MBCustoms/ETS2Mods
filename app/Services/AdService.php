<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class AdService
{
    /**
     * Get the HTML content for a specific ad slot.
     *
     * @param string $slotName
     * @return string|null
     */
    public function getAdContent(string $slotName): ?string
    {
        // 1. Check Global Rules
        if (!$this->isAdsEnabled()) {
            return null;
        }

        // 2. Check User Verification Rules
        if (Auth::check() && Auth::user()->is_verified) {
             // If setting 'ads.hide_for_verified' is true, return null
             if ($this->getSetting('ads.hide_for_verified', false)) {
                 return null;
             }
        }
        
        // 3. Check Logged In User Rules (General)
        if (Auth::check() && !$this->getSetting('ads.show_for_users', true)) {
             return null;
        }

        // 4. Check Guest Rules
        if (!Auth::check() && !$this->getSetting('ads.show_for_guests', true)) {
            return null;
        }

        // 5. Get Slot Content
        $content = $this->getSetting("ads.slot_{$slotName}");

        if (empty($content)) {
            return null;
        }

        return $content;
    }

    /**
     * Get the global head script (e.g., AdSense auto ads).
     */
    public function getHeadScript(): ?string
    {
        if (!$this->isAdsEnabled()) {
            return null;
        }

        return $this->getSetting('ads.head_script');
    }

    private function isAdsEnabled(): bool
    {
        return (bool) $this->getSetting('ads.enabled', false);
    }

    private function getSetting(string $key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $value = Setting::where('group', 'ads')->where('key', $key)->value('value');
            return $value ?? $default;
        });
    }
    
    /**
     * Update an ad setting (Admin only helper).
     */
    public function updateSetting(string $key, $value)
    {
        Setting::updateOrCreate(
            ['group' => 'ads', 'key' => $key],
            ['value' => $value, 'type' => 'string', 'is_public' => false]
        );
        Cache::forget("settings.{$key}");
    }
}

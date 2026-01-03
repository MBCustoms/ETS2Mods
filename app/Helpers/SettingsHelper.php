<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database with caching
     *
     * @param string $key Setting key in group.key format (e.g., 'site.name')
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        // Load all settings into cache if not already cached
        $settings = Cache::rememberForever('app.settings', function () {
            return Setting::all()->groupBy('group')->map(function ($group) {
                return $group->pluck('value', 'key')->map(function ($value, $key) use ($group) {
                    $setting = $group->where('key', $key)->first();
                    return Setting::castValue($value, $setting->type ?? 'string');
                });
            })->toArray();
        });

        // Parse the key (e.g., 'site.name' => group: 'site', key: 'name')
        if (!str_contains($key, '.')) {
            return $default;
        }

        [$group, $settingKey] = explode('.', $key, 2);

        return $settings[$group][$settingKey] ?? $default;
    }
}

if (!function_exists('setting_set')) {
    /**
     * Set a setting value and clear cache
     *
     * @param string $group Setting group
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @param string $type Value type (string, boolean, integer, float, json)
     * @return void
     */
    function setting_set(string $group, string $key, mixed $value, string $type = 'string'): void
    {
        Setting::set($group, $key, $value, $type);
    }
}

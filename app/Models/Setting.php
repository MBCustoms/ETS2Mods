<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by group.key notation
     */
    public static function get(string $key, $default = null)
    {
        [$group, $settingKey] = explode('.', $key);
        
        $setting = static::where('group', $group)
            ->where('key', $settingKey)
            ->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $group, string $key, $value, string $type = 'string'): void
    {
        static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'type' => $type]
        );

        static::clearCache();
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('app.settings');
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

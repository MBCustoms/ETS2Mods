<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Site Settings
            ['group' => 'site', 'key' => 'name', 'value' => 'ETS2LT Mods', 'type' => 'string', 'is_public' => true],
            ['group' => 'site', 'key' => 'tagline', 'value' => 'Your trusted source for simulation game mods', 'type' => 'string', 'is_public' => true],
            ['group' => 'site', 'key' => 'contact_email', 'value' => 'contact@ets2lt.com', 'type' => 'string', 'is_public' => true],
            ['group' => 'site', 'key' => 'logo', 'value' => '/images/logo.png', 'type' => 'string', 'is_public' => true],
            
            // SEO Settings
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Download the best mods for Euro Truck Simulator 2 and American Truck Simulator', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'ets2, ats, mods, truck simulator, euro truck simulator 2', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'value' => '', 'type' => 'string', 'is_public' => true],
            
            // Mail Settings
            ['group' => 'mail', 'key' => 'smtp_host', 'value' => 'smtp.mailtrap.io', 'type' => 'string', 'is_public' => false],
            ['group' => 'mail', 'key' => 'smtp_port', 'value' => '2525', 'type' => 'integer', 'is_public' => false],
            ['group' => 'mail', 'key' => 'smtp_username', 'value' => '', 'type' => 'string', 'is_public' => false],
            ['group' => 'mail', 'key' => 'smtp_password', 'value' => '', 'type' => 'string', 'is_public' => false],
            ['group' => 'mail', 'key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'string', 'is_public' => false],
            
            // Ads Settings
            ['group' => 'ads', 'key' => 'header_code', 'value' => '', 'type' => 'string', 'is_public' => false],
            ['group' => 'ads', 'key' => 'sidebar_code', 'value' => '', 'type' => 'string', 'is_public' => false],
            ['group' => 'ads', 'key' => 'footer_code', 'value' => '', 'type' => 'string', 'is_public' => false],
            
            // reCAPTCHA Settings
            ['group' => 'recaptcha', 'key' => 'enabled', 'value' => 'false', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'recaptcha', 'key' => 'site_key', 'value' => '', 'type' => 'string', 'is_public' => true],
            ['group' => 'recaptcha', 'key' => 'secret_key', 'value' => '', 'type' => 'string', 'is_public' => false],
            
            // Security Settings
            ['group' => 'security', 'key' => 'mod_submission_rate_limit', 'value' => '5', 'type' => 'integer', 'is_public' => false],
            ['group' => 'security', 'key' => 'download_rate_limit', 'value' => '10', 'type' => 'integer', 'is_public' => false],
            ['group' => 'security', 'key' => 'report_rate_limit', 'value' => '3', 'type' => 'integer', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}

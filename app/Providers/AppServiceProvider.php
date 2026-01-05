<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Force file driver if not installed to prevent DB connection errors
        if (!file_exists(storage_path('installed'))) {
            config([
                'session.driver' => 'file',
                'cache.default' => 'file',
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share active pages with all views (for navigation)
        // Share global settings with all views
        // Check if installed before trying to load settings from DB
        if (!file_exists(storage_path('installed'))) {
            return;
        }

        // Share active pages with all views (for navigation)
        // Share global settings with all views
        View::composer('*', function ($view) {
            try {
                $settings = Cache::remember('app.settings', 3600, function () {
                    return \App\Models\Setting::all()->groupBy('group');
                });
                
                // Ensure robust handling if cache returns array or collection
                $settings = collect($settings);
                $siteSettings = collect($settings->get('site'));
                
                $view->with('siteSettings', $siteSettings);
    
                // Specific shortcuts for ease of use
                $appName = config('app.name');
                if ($nameSetting = $siteSettings->where('key', 'name')->first()) {
                    $appName = $nameSetting['value'] ?? $nameSetting->value ?? $appName;
                }
                $view->with('appName', $appName);
    
                $appLogo = null;
                if ($logoSetting = $siteSettings->where('key', 'logo')->first()) {
                    $appLogo = $logoSetting['value'] ?? $logoSetting->value ?? null;
                }
                $view->with('appLogo', $appLogo);
    
                $appFavicon = null;
                if ($faviconSetting = $siteSettings->where('key', 'favicon')->first()) {
                    $appFavicon = $faviconSetting['value'] ?? $faviconSetting->value ?? null;
                }
                $view->with('appFavicon', $appFavicon);
                
                // Also share active pages for navigation (moved from layouts.app only)
                 $activePages = Cache::remember('active_pages', 3600, function () {
                    return Page::where('is_active', true)
                        ->orderBy('title')
                        ->get();
                });
                $view->with('activePages', $activePages);
            } catch (\Exception $e) {
                // Fail silently if DB not ready (extra safety)
            }
        });
        
        // Override mail config from settings if they exist
        try {
            if (function_exists('setting')) {
                $mailer = setting('mail.mailer');
                if ($mailer) {
                    config(['mail.default' => $mailer]);
                }
    
                $host = setting('mail.host');
                if ($host) {
                    config(['mail.mailers.smtp.host' => $host]);
                }
    
                $port = setting('mail.port');
                if ($port) {
                    config(['mail.mailers.smtp.port' => $port]);
                }
    
                $username = setting('mail.username');
                if ($username) {
                    config(['mail.mailers.smtp.username' => $username]);
                }
    
                $password = setting('mail.password');
                if ($password) {
                    config(['mail.mailers.smtp.password' => $password]);
                }
    
                $encryption = setting('mail.encryption');
                if ($encryption !== null) {
                    config(['mail.mailers.smtp.encryption' => $encryption]);
                }
    
                $fromAddress = setting('mail.from.address');
                if ($fromAddress) {
                    config(['mail.from.address' => $fromAddress]);
                }
    
                $fromName = setting('mail.from.name');
                if ($fromName) {
                    config(['mail.from.name' => $fromName]);
                }
    
                // Override ReCaptcha config
                $siteKey = setting('recaptcha.site_key');
                $secretKey = setting('recaptcha.secret_key');
                if ($siteKey && $secretKey) {
                    config(['captcha.sitekey' => $siteKey]); // package uses 'captcha.sitekey'
                    config(['captcha.secret' => $secretKey]);   // package uses 'captcha.secret'
                }
            }
        } catch (\Exception $e) {
            // Fail silently
        }
    }
}

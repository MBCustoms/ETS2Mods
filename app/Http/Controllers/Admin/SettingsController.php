<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:512',
            
            // Site Settings
            'settings.site.name' => 'nullable|string|max:255',
            'settings.site.email' => 'nullable|email|max:255',
            
            // SEO Settings
            'settings.seo.meta_description' => 'nullable|string|max:500',
            'settings.seo.meta_keywords' => 'nullable|string|max:255',
            
            // Recaptcha Settings
            'settings.recaptcha.enabled' => 'nullable|boolean',
            'settings.recaptcha.site_key' => 'nullable|string|max:255',
            'settings.recaptcha.secret_key' => 'nullable|string|max:255',
            'settings.recaptcha.version' => 'nullable|string|in:v2,v3',
            
            // Redirect Settings
            'settings.redirect.text' => 'nullable|string|max:255',
            'settings.redirect.timer' => 'nullable|integer|min:0|max:60',
        ]);

        $imageUploadService = app(\App\Services\ImageUploadService::class);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $imageUploadService->uploadLogo($request->file('logo'));
            
            // Delete old logo if exists
            $oldLogo = Setting::where('group', 'site')->where('key', 'logo')->first();
            if ($oldLogo && $oldLogo->value) {
                $imageUploadService->deleteImage($oldLogo->value);
            }
            
            Setting::updateOrCreate(
                ['group' => 'site', 'key' => 'logo'],
                ['value' => $logoPath, 'type' => 'string']
            );
            Cache::forget('app.settings');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconPath = $imageUploadService->uploadFavicon($request->file('favicon'));
            
            // Delete old favicon if exists
            $oldFavicon = Setting::where('group', 'site')->where('key', 'favicon')->first();
            if ($oldFavicon && $oldFavicon->value) {
                $imageUploadService->deleteImage($oldFavicon->value);
            }
            
            Setting::updateOrCreate(
                ['group' => 'site', 'key' => 'favicon'],
                ['value' => $faviconPath, 'type' => 'string']
            );
            Cache::forget('app.settings');
        }

        foreach ($validated['settings'] as $group => $keys) {
            foreach ($keys as $key => $value) {
                // Get setting type if exists
                $existingSetting = Setting::where('group', $group)->where('key', $key)->first();
                $type = $existingSetting->type ?? 'string';
                
                Setting::updateOrCreate(
                    ['group' => $group, 'key' => $key],
                    ['value' => $value, 'type' => $type]
                );
                
                Cache::forget("settings.{$group}.{$key}");
            }
        }
        
        Cache::forget('app.settings'); // Clear all settings cache
        
        return back()->with('success', 'Settings updated successfully.');
    }
}

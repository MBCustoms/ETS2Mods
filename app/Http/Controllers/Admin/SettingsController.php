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
        ]);

        foreach ($validated['settings'] as $group => $keys) {
            foreach ($keys as $key => $value) {
                // If checkbox unchecked, it might not be sent, so we handle that in logic or assume sent values are strings
                Setting::updateOrCreate(
                    ['group' => $group, 'key' => $key],
                    ['value' => $value]
                );
                
                Cache::forget("settings.{$group}.{$key}"); // Invalidate cache
            }
        }
        
        // Handle checkboxes/booleans that are unchecked (if needed, or handle in frontend by sending hidden 0)
        
        return back()->with('success', 'Settings updated successfully.');
    }
}

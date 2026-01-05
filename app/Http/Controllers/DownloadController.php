<?php

namespace App\Http\Controllers;

use App\Models\Mod;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index(Request $request, Mod $mod)
    {
        $index = $request->query('index');
        
        // Determine target URL and Label
        $targetUrl = null;
        $linkName = 'External Download';

        if ($index !== null && !empty($mod->download_links) && isset($mod->download_links[$index])) {
            $link = $mod->download_links[$index];
            $targetUrl = $link['url'] ?? null;
            $linkName = $link['label'] ?? 'Download Mirror ' . ($index + 1);
        } else {
            // Fallback to main download_url
            // Check latest version first
            if ($mod->latestVersion && $mod->latestVersion->download_url) {
                $targetUrl = $mod->latestVersion->download_url;
            } else {
                $targetUrl = $mod->download_url;
            }
            $linkName = 'Main Download';
        }

        if (!$targetUrl) {
            return redirect()->route('mods.show', $mod)->with('error', 'Download link not found.');
        }

        // Get Settings
        $settings = collect(cache('app.settings')); // Assuming cached form from AppServiceProvider logic
        $redirectSettings = collect($settings->get('redirect'));
        
        $redirectText = $redirectSettings->where('key', 'text')->first()['value'] 
                        ?? $redirectSettings->where('key', 'text')->first()->value 
                        ?? 'Preparing your download...';
                        
        $timer = $redirectSettings->where('key', 'timer')->first()['value'] 
                 ?? $redirectSettings->where('key', 'timer')->first()->value 
                 ?? 5;

        // Increment download count (optional, prevent spam?)
        // simplified: increment on view
        $mod->increment('downloads_count');

        return view('download.redirect', compact('mod', 'targetUrl', 'linkName', 'redirectText', 'timer'));
    }
}

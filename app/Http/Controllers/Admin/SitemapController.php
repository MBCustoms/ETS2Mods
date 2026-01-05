<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Mod;
use App\Models\Page;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $path = public_path('sitemap.xml');
        $exists = File::exists($path);
        $lastGenerated = $exists ? File::lastModified($path) : null;
        $url = url('sitemap.xml');
        $cronUrl = route('admin.sitemap.generate.cron', ['key' => config('app.key')]); // Simple protection using app key

        return view('admin.sitemap.index', compact('exists', 'lastGenerated', 'url', 'cronUrl'));
    }

    public function generate()
    {
        $this->generateSitemap();
        return back()->with('success', 'Sitemap generated successfully.');
    }

    public function cron($key)
    {
        if ($key !== config('app.key')) {
            abort(403, 'Invalid key');
        }

        $this->generateSitemap();
        return response()->json(['success' => true, 'message' => 'Sitemap generated']);
    }

    private function generateSitemap()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Static Pages
        $this->addUrl($content, route('home'), '1.0', 'daily');
        $this->addUrl($content, route('contact.index'), '0.5', 'monthly');

        // Dynamic Pages
        $pages = Page::where('is_active', true)->get();
        foreach ($pages as $page) {
            $this->addUrl($content, route('pages.show', $page->slug), '0.6', 'weekly');
        }

        // Categories
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->addUrl($content, route('categories.show', $category->slug), '0.8', 'daily');
        }

        // Mods
        $mods = Mod::approved()->get();
        foreach ($mods as $mod) {
            // Check if route exists, assuming 'mods.show' uses slug or id
            // Route logic usually: /mods/{mod}
            $this->addUrl($content, route('mods.show', $mod), '0.9', 'weekly', $mod->updated_at);
        }

        $content .= '</urlset>';

        File::put(public_path('sitemap.xml'), $content);
    }

    private function addUrl(&$content, $url, $priority, $freq, $lastmod = null)
    {
        $content .= '  <url>' . PHP_EOL;
        $content .= '    <loc>' . $url . '</loc>' . PHP_EOL;
        if ($lastmod) {
            $content .= '    <lastmod>' . $lastmod->toIso8601String() . '</lastmod>' . PHP_EOL;
        } else {
            $content .= '    <lastmod>' . now()->toIso8601String() . '</lastmod>' . PHP_EOL;
        }
        $content .= '    <changefreq>' . $freq . '</changefreq>' . PHP_EOL;
        $content .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $content .= '  </url>' . PHP_EOL;
    }
}

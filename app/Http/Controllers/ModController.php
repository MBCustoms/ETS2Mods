<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Mod;
use App\Rules\ValidateDownloadUrl;
use App\Services\ModService;
use App\Services\DownloadSecurityService; // Added this use statement
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ModController extends Controller
{
    use AuthorizesRequests;

    protected $modService;
    protected $downloadSecurity; // Added this property

    public function __construct(ModService $modService, \App\Services\DownloadSecurityService $downloadSecurity) // Updated constructor
    {
        $this->modService = $modService;
        $this->downloadSecurity = $downloadSecurity; // Assigned new service
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mods = Mod::approved()
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->with(['user', 'category', 'modImages'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->game_version, function ($query, $version) {
                $query->whereHas('versions', function ($q) use ($version) {
                    $q->where('game_version', $version);
                });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->ordered()->get();

        return view('mods.index', compact('mods', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Mod::class);

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        $categories = Category::active()->ordered()->get();
        
        return view('mods.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Mod::class);

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $rules = [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'version_number' => 'required|string|max:20',
            'game_version' => 'nullable|string|max:50',
            'file_size' => 'nullable|string|max:50',
            'credits' => 'nullable|string|max:255',
            'download_url' => ['required', 'url', new ValidateDownloadUrl],
            'youtube_videos' => 'nullable|array',
            'youtube_videos.*' => 'nullable|url',
            'download_links' => 'nullable|array',
            'download_links.*.label' => 'nullable|string|max:50',
            'download_links.*.url' => 'nullable|url',
            'images.*' => 'image|max:5120', // 5MB
        ];

        if (setting('recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validated = $request->validate($rules);
        unset($validated['g-recaptcha-response']);

        // Attach uploaded files and optional credits into validated data for the service
        $validated['images'] = $request->file('images') ?? [];
        if ($request->filled('credits')) {
            $validated['credits'] = $request->input('credits');
        }
        
        // Filter empty YouTube video URLs
        if (isset($validated['youtube_videos'])) {
            $validated['youtube_videos'] = array_filter($validated['youtube_videos'], function($url) {
                return !empty(trim($url));
            });
            if (empty($validated['youtube_videos'])) {
                $validated['youtube_videos'] = null;
            }
        }

        // Filter empty Download Links
        if (isset($validated['download_links'])) {
            $validated['download_links'] = array_filter($validated['download_links'], function($link) {
                return !empty($link['url']);
            });
            $validated['download_links'] = !empty($validated['download_links']) ? array_values($validated['download_links']) : null;
        }

        $mod = $this->modService->createMod($validated, $request->user());

        return redirect()->route('mods.show', $mod)
            ->with('success', 'Mod submitted successfully! It will be reviewed by our team shortly.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mod $mod)
    {
        // Only increment views for approved mods
        if ($mod->status === 'approved') {
            $mod->increment('views_count');
        }
        
        $mod->load(['user', 'category', 'modImages', 'versions' => function ($query) {
            $query->latest();
        }, 'comments.user']);

        $images = $mod->modImages;
        $similar = Mod::where('category_id', $mod->category_id)
            ->where('id', '!=', $mod->id)
            ->where('status', 'approved')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('mods.show', compact('mod', 'images', 'similar'));
    }

    /**
     * Redirect to download link and increment counter.
     */
    public function download(Mod $mod)
    {
        $this->authorize('view', $mod);

        $shouldCount = $this->downloadSecurity->recordDownload(
            $mod,
            request()->ip(),
            request()->user()
        );

        if ($shouldCount) {
             $this->modService->incrementDownloads($mod);
        }

        // Handle version specific URL if needed, or just default mod URL
        // Ideally we should be downloading a specific version, but for now latest or main URL
        // The migration logic put URL in mod_versions but we also kept it in mods? 
        // Let's assume Mod URL is valid fallback or primary if no versions.
        // Actually, previous refactor moved data to 'latestVersion'.
        // We should check if we download 'latest' or specific. Route is /mods/{mod}/download.
        // Let's use latest version URL if available.
        $url = $mod->latestVersion?->download_url ?? $mod->download_url;

        return redirect()->away($url);
    }

    /**
     * Display user's own mods
     */
    public function myMods(Request $request)
    {
        $user = $request->user();
        
        $mods = Mod::where('user_id', $user->id)
            ->with(['category', 'modImages'])
            ->latest()
            ->paginate(12);

        return view('mods.my-mods', compact('mods'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mod $mod)
    {
        $this->authorize('update', $mod);
        
        $categories = Category::active()->ordered()->get();
        $mod->load('modImages', 'latestVersion');
        
        return view('mods.edit', compact('mod', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mod $mod)
    {
        $this->authorize('update', $mod);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'download_url' => 'required|url',
            'download_links' => 'nullable|array',
            'download_links.*.url' => 'nullable|url',
            'download_links.*.label' => 'nullable|string|max:255',
            'youtube_videos' => 'nullable|array',
            'youtube_videos.*' => 'nullable|url',
            'version_number' => 'nullable|string|max:50',
            'game_version' => 'nullable|string|max:50',
            'file_size' => 'nullable|string|max:50',
            'images.*' => 'nullable|image|max:5120',
        ]);

        // If mod was rejected and user is editing it, set it back to pending
        if ($mod->status === 'rejected' && !$request->user()->hasRole(['admin', 'moderator'])) {
            $validated['status'] = 'pending';
        }

        // Filter empty Download Links
        if (isset($validated['download_links'])) {
            $validated['download_links'] = array_filter($validated['download_links'], function($link) {
                return !empty($link['url']);
            });
            $validated['download_links'] = !empty($validated['download_links']) ? array_values($validated['download_links']) : null;
        }

        // Filter out empty YouTube videos
        if (isset($validated['youtube_videos'])) {
            $validated['youtube_videos'] = array_filter($validated['youtube_videos'], function ($video) {
                return !empty($video);
            });
            $validated['youtube_videos'] = array_values($validated['youtube_videos']);
        }

        // Update mod
        $mod->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
    'download_links' => $validated['download_links'] ?? null,
        ]);

        // Update latest version download URL
        if ($mod->latestVersion) {
            $mod->latestVersion->update([
                'download_url' => $validated['download_url'],
            ]);
        }

        // Handle image deletions
        if ($request->has('remove_images')) {
            $imagesToDelete = $mod->modImages()->whereIn('id', $request->remove_images)->get();
            $imageUploadService = app(\App\Services\ImageUploadService::class);
            
            foreach ($imagesToDelete as $image) {
                // Delete from storage
                $imageUploadService->deleteImage($image->path);
                // Delete from DB
                $image->delete();
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $imageUploadService = app(\App\Services\ImageUploadService::class);
            $existingCount = $mod->modImages()->count();
            
            foreach ($request->file('images') as $index => $image) {
                $path = $imageUploadService->uploadImage($image, $mod->title);
                $mod->modImages()->create([
                    'path' => $path,
                    'order' => $existingCount + $index,
                    'is_main' => ($existingCount === 0 && $index === 0), // Only if no existing images
                ]);
            }
        }

        return redirect()->route('mods.show', $mod)
            ->with('success', 'Mod updated successfully!');
    }
}

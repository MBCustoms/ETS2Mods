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
        
        $categories = Category::active()->ordered()->get();
        
        return view('mods.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Mod::class);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'version_number' => 'required|string|max:20',
            'game_version' => 'nullable|string|max:50',
            'file_size' => 'nullable|string|max:50',
            'credits' => 'nullable|string|max:255',
            'download_url' => ['required', 'url', new ValidateDownloadUrl],
            'images.*' => 'image|max:5120', // 5MB
        ]);

        // Attach uploaded files and optional credits into validated data for the service
        $validated['images'] = $request->file('images') ?? [];
        if ($request->filled('credits')) {
            $validated['credits'] = $request->input('credits');
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
        $this->authorize('view', $mod);

        $this->modService->incrementViews($mod);
        
        $similar = $this->modService->getSimilarMods($mod);
        
        // Eager load images
        $mod->load('modImages');

        return view('mods.show', compact('mod', 'similar'));
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
}

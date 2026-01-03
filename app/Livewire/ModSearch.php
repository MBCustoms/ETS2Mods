<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Mod;
use App\Models\ModVersion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class ModSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $version = '';
    public $sort = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'version' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function updated($property)
    {
        if (in_array($property, ['search', 'category', 'version', 'sort'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $mods = Mod::query()
            ->approved() // Only show approved and published mods
            ->when($this->search, function (Builder $query) {
                $query->search($this->search);
            })
            ->when($this->category, function (Builder $query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->version, function (Builder $query) {
                $query->whereHas('versions', function ($q) {
                    $q->where('game_version', $this->version);
                });
            })
            ->when($this->sort, function (Builder $query) {
                switch ($this->sort) {
                    case 'relevance':
                        // Only applicable if searching, handled by search scope usually, 
                        // but if we want explicit ordering by relevance when strict mode is off:
                        // For boolean mode, results are not automatically sorted by relevance unless we select the score.
                        // However, for simplicity in this phase, we won't do complex score sorting unless requested.
                        // We will fallback to newest if relevance is selected but no search term.
                        if ($this->search) {
                            // If we need relevance sort, we need to select raw match score.
                            // For now, let's just stick to default fulltext behavior or fallback to newest.
                             $query->orderBy('published_at', 'desc');
                        } else {
                            $query->orderBy('published_at', 'desc');
                        }
                        break;
                    case 'popular':
                        $query->orderBy('downloads_count', 'desc');
                        break;
                    case 'top_rated':
                        $query->orderBy('reviews_avg', 'desc');
                        break;
                    case 'newest':
                    default:
                        $query->orderBy('published_at', 'desc');
                        break;
                }
            })
            ->with(['user', 'category', 'modImages'])
            ->paginate(12);

        $categories = Category::active()->ordered()->get();
        // Get unique game versions from the mod_versions table
        $versions = ModVersion::select('game_version')->distinct()->orderBy('game_version', 'desc')->pluck('game_version');

        return view('mods.index', [
            'mods' => $mods,
            'categories' => $categories,
        ]);
    }
}

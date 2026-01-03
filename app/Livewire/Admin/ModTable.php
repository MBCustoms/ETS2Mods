<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Mod;
use Livewire\Component;
use Livewire\WithPagination;

class ModTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $category = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $mods = Mod::query()
            ->with(['user', 'category'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.mod-table', [
            'mods' => $mods,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}

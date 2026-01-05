<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PageManager extends Component
{
    use WithPagination;

    public $pageId = null;
    public $slug = '';
    public $title = '';
    public $content = '';
    public $meta_title = '';
    public $meta_description = '';
    public $is_active = true;
    public $showModal = false;
    public $isEditing = false;

    protected $rules = [
        'slug' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function updatedTitle()
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $this->pageId = $page->id;
        $this->slug = $page->slug;
        $this->title = $page->title;
        $this->content = $page->content;
        $this->meta_title = $page->meta_title;
        $this->meta_description = $page->meta_description;
        $this->is_active = $page->is_active;
        $this->showModal = true;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $page = Page::findOrFail($this->pageId);
            $page->update([
                'slug' => $this->slug,
                'title' => $this->title,
                'content' => $this->content,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'is_active' => $this->is_active,
                'updated_by' => auth()->id(),
            ]);
            session()->flash('success', 'Page updated successfully!');
        } else {
            Page::create([
                'slug' => $this->slug,
                'title' => $this->title,
                'content' => $this->content,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'is_active' => $this->is_active,
                'updated_by' => auth()->id(),
            ]);
            session()->flash('success', 'Page created successfully!');
        }

        Cache::forget('active_pages');
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Page::findOrFail($id)->delete();
        Cache::forget('active_pages');
        session()->flash('success', 'Page deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->update(['is_active' => !$page->is_active]);
        Cache::forget('active_pages');
    }

    public function resetForm()
    {
        $this->pageId = null;
        $this->slug = '';
        $this->title = '';
        $this->content = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.page-manager', [
            'pages' => Page::latest()->paginate(10),
        ])->layout('layouts.admin');
    }
}

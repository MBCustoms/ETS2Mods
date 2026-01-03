<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;

    public $name, $slug, $description, $icon, $order;
    public $is_active = true;
    public $categoryId;
    public $isOpen = false;

    public function render()
    {
        return view('livewire.admin.category-table', [
            'categories' => Category::orderBy('order')->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->icon = '';
        $this->order = '';
        $this->is_active = true;
        $this->categoryId = null;
    }

    public function updatedName($value)
    {
        if (!$this->categoryId) { // Only auto-generate slug on create
            $this->slug = Str::slug($value);
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $this->categoryId,
            'order' => 'integer',
        ]);

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'slug' => $this->slug, // Should strictly use Str::slug() but let's trust input for now or better enforce it
            'description' => $this->description,
            'icon' => $this->icon,
            'order' => $this->order ?? 0,
            'is_active' => $this->is_active ? 1 : 0,
        ]);

        session()->flash('message', 
            $this->categoryId ? 'Category Updated Successfully.' : 'Category Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->order = $category->order;
        $this->is_active = $category->is_active;

        $this->openModal();
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Category Deleted Successfully.');
    }

    public function toggleActive($id)
    {
        $category = Category::find($id);
        $category->is_active = !$category->is_active;
        $category->save();
    }
}

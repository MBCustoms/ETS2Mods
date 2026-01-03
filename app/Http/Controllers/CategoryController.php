<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Mod;

class CategoryController extends Controller
{
    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $mods = $category->mods()
            ->approved()
            ->latest()
            ->paginate(12);

        $categories = Category::active()->ordered()->get();

        return view('mods.index', compact('mods', 'categories', 'category'));
    }
}

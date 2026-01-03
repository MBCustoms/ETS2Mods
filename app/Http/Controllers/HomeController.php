<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Mod;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        $mods = Mod::approved()
            ->with(['user', 'category', 'modImages'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->ordered()->get();

        return view('mods.index', compact('mods', 'categories'));
    }
}

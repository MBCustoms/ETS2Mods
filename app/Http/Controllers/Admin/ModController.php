<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Mod;

class ModController extends Controller
{
    public function index()
    {
        return view('admin.mods.index');
    }

    public function edit(Mod $mod)
    {
        return view('admin.mods.edit', compact('mod'));
    }

    public function update(Request $request, Mod $mod)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
        ]);

        $mod->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejected' ? $validated['rejection_reason'] : null,
            'category_id' => $validated['category_id'],
            'is_featured' => $request->has('is_featured'),
        ]);

        if ($validated['status'] === 'approved' && is_null($mod->published_at)) {
            $mod->update(['published_at' => now()]);
        }

        return redirect()->route('admin.mods.index')->with('success', 'Mod updated successfully');
    }
}

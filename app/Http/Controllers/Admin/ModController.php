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
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'credits' => 'nullable|string|max:500',
            'download_url' => 'required|url',
            'youtube_videos' => 'nullable|array',
            'youtube_videos.*' => 'nullable|url',
            'download_links' => 'nullable|array',
            'download_links.*.label' => 'nullable|string|max:50',
            'download_links.*.url' => 'nullable|url',
            'images.*' => 'image|max:5120',
        ]);

        $youtubeVideos = null;
        if (isset($validated['youtube_videos'])) {
            $filtered = array_filter($validated['youtube_videos'], fn($url) => !empty(trim($url)));
            $youtubeVideos = !empty($filtered) ? array_values($filtered) : null;
        }

        $downloadLinks = null;
        if (isset($validated['download_links'])) {
            $filteredLinks = array_filter($validated['download_links'], function($link) {
                return !empty($link['url']);
            });
            $downloadLinks = !empty($filteredLinks) ? array_values($filteredLinks) : null;
        }

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'is_featured' => $request->has('is_featured'),
            'credits' => $validated['credits'] ?? null,
            'youtube_videos' => $youtubeVideos,
            'download_url' => $validated['download_url'], // Assuming column exists or we set on latestVersion
            'download_links' => $downloadLinks,
        ];

        // Handle rejection reason
        if ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        } else {
            $updateData['rejection_reason'] = null;
        }

        $mod->update($updateData);

        // Update latest version URL as well if exists
        if ($mod->latestVersion) {
            $mod->latestVersion->update(['download_url' => $validated['download_url']]);
        }

        // Handle image deletions
        if ($request->has('remove_images')) {
            $imagesToDelete = $mod->modImages()->whereIn('id', $request->remove_images)->get();
            $imageUploadService = app(\App\Services\ImageUploadService::class);
            foreach ($imagesToDelete as $image) {
                $imageUploadService->deleteImage($image->path);
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
                    'is_main' => ($existingCount === 0 && $index === 0),
                ]);
            }
        }

        // Set published_at if approved for the first time
        if ($validated['status'] === 'approved' && is_null($mod->published_at)) {
            $mod->update(['published_at' => now()]);
        }

        return redirect()->route('admin.mods.index')->with('success', 'Mod updated successfully');
    }

    public function destroy(Mod $mod)
    {
        $mod->delete();
        return redirect()->route('admin.mods.index')->with('success', 'Mod deleted successfully');
    }
}

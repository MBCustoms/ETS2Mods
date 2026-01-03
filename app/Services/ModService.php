<?php

namespace App\Services;

use App\Models\Mod;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Cache;

class ModService
{
    /**
     * Create a new mod with pending status
     */
    public function createMod(array $data, $user): Mod
    {
        $mod = $user->mods()->create([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'credits' => $data['credits'] ?? null,
            'status' => 'pending',
            // Deprecated fields are handled via versions now
        ]);

        // Create initial version (v1.0.0 default if not provided)
        $mod->versions()->create([
            'version_number' => $data['version_number'] ?? '1.0.0',
            'game_version' => $data['game_version'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'download_url' => $data['download_url'],
            'changelog' => 'Initial release',
            'is_active' => true,
        ]);

        // Handle image uploads if provided
        if (isset($data['images']) && is_array($data['images'])) {
            $imageUploadService = app(ImageUploadService::class);
            foreach ($data['images'] as $index => $image) {
                $path = $imageUploadService->uploadImage($image, $mod->title);
                $mod->modImages()->create([
                    'path' => $path,
                    'order' => $index,
                    'is_main' => $index === 0, // First image is main
                ]);
            }
        }

        return $mod;
    }

    /**
     * Approve a mod
     */
    public function approveMod(Mod $mod): void
    {
        $mod->update([
            'status' => 'approved',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject a mod with reason
     */
    public function rejectMod(Mod $mod, string $reason): void
    {
        $mod->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'published_at' => null,
        ]);
    }

    /**
     * Get similar mods by category
     */
    public function getSimilarMods(Mod $mod, int $limit = 6)
    {
        return Mod::approved()
            ->with('modImages')
            ->where('category_id', $mod->category_id)
            ->where('id', '!=', $mod->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Increment view counter
     */
    public function incrementViews(Mod $mod): void
    {
        $mod->incrementViews();
    }

    /**
     * Increment download counter
     */
    /**
     * Increment download counter
     */
    public function incrementDownloads(Mod $mod): void
    {
        $mod->incrementDownloads();
    }

    /**
     * Add a new version to a mod
     */
    public function addVersion(Mod $mod, array $data): void
    {
        $mod->versions()->create([
            'version_number' => $data['version_number'],
            'game_version' => $data['game_version'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'download_url' => $data['download_url'],
            'changelog' => $data['changelog'] ?? null,
            'is_active' => true,
        ]);

        // Touch the mod to update updated_at
        $mod->touch();
        
        // Notify followers
        $followers = $mod->followers()->with('user')->get();
        foreach ($followers as $follow) {
            $follow->user->notify(new \App\Notifications\ModUpdatedNotification($mod, $data['version_number']));
        }
    }
}

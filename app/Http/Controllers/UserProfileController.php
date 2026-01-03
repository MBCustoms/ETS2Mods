<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function show(User $user)
    {
        // Eager load mods with media and category
        $mods = $user->mods()
            ->approved()
            ->latest('published_at')
            ->with(['category', 'modImages'])
            ->paginate(9);

        // Calculate stats
        $stats = [
            'total_downloads' => $user->mods()->sum('downloads_count'),
            'avg_rating' => $user->mods()->where('reviews_count', '>', 0)->avg('reviews_avg') ?? 0,
            'mods_count' => $user->mods()->approved()->count(),
        ];

        // Get badges
        $badges = $this->badgeService->getBadges($user);

        return view('users.show', compact('user', 'mods', 'stats', 'badges'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $imageUploadService = app(\App\Services\ImageUploadService::class);
            
            // Delete old avatar if exists
            if ($user->avatar) {
                $imageUploadService->deleteImage($user->avatar);
            }
            
            $validated['avatar'] = $imageUploadService->uploadAvatar($request->file('avatar'), $user->name);
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'Profile updated successfully!');
    }
}

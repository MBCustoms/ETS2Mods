<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
            'avg_rating' => $user->reviews_avg ?? 0,
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
            'social_links' => 'nullable|array',
            'social_links.facebook' => 'nullable|url|max:255',
            'social_links.twitter' => 'nullable|url|max:255',
            'social_links.instagram' => 'nullable|url|max:255',
            'social_links.youtube' => 'nullable|url|max:255',
            'social_links.website' => 'nullable|url|max:255',
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Validate current password if password change is requested
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'The current password is incorrect.',
                ])->withInput();
            }
        }

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

        // Handle password change
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password'], $validated['current_password'], $validated['password_confirmation']);
        }

        // Handle social links - filter out empty values
        if (isset($validated['social_links'])) {
            $validated['social_links'] = array_filter($validated['social_links'], function($value) {
                return !empty($value);
            });
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Display user's followings
     */
    public function followings(Request $request)
    {
        $user = $request->user();
        
        $followings = $user->following()
            ->with(['followable'])
            ->latest()
            ->paginate(20);

        return view('users.followings', compact('followings'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(15); // Kept original logic, but used `User` alias
        return view('admin.users.index', compact('users'));
    }

    public function toggleVerified(User $user) // Used `User` alias
    {
        $user->update(['is_verified' => !$user->is_verified]);
        
        return back()->with('success', 'User verification status updated.');
    }

    public function toggleShadowBan(User $user) // Added new method
    {
        $user->update([
            'shadow_banned_at' => $user->shadow_banned_at ? null : now()
        ]);
        return back()->with('success', 'User shadow ban status updated.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        
        // Calculate user statistics
        $stats = [
            'total_mods' => $user->mods()->count(),
            'approved_mods' => $user->mods()->approved()->count(),
            'pending_mods' => $user->mods()->pending()->count(),
            'rejected_mods' => $user->mods()->rejected()->count(),
            'total_downloads' => $user->mods()->sum('downloads_count'),
            'total_views' => $user->mods()->sum('views_count'),
            'total_comments' => $user->mods()->withCount('comments')->get()->sum('comments_count'),
            'total_ratings' => $user->mods()->withCount('ratings')->get()->sum('ratings_count'),
            'avg_rating' => $user->mods()->whereHas('ratings')->withAvg('ratings', 'rating')->get()->avg('ratings_avg_rating') ?? 0,
            'total_reports' => $user->reports()->count(),
            'total_following' => $user->following()->count(),
            'total_followers' => \App\Models\Follow::where('followable_type', User::class)
                ->where('followable_id', $user->id)
                ->count(),
        ];

        return view('admin.users.edit', compact('user', 'roles', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'is_verified' => 'boolean',
            'is_banned' => 'boolean',
            'warning_count' => 'nullable|integer|min:0|max:10',
            'roles' => 'nullable|array',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'avatar' => 'nullable|image|max:2048', // 2MB Max
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = app(\App\Services\ImageUploadService::class)->upload(
                $request->file('avatar'), 
                'avatars', 
                800, // Max width
                800  // Max height
            );
            $validated['avatar_path'] = $path;
            
            // Optional: Delete old avatar if exists
            if ($user->avatar_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
            }
        }

        // Handle password change
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password'], $validated['password_confirmation']);
        }

        // Handle roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        // Update user
        $user->update($validated);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'User updated successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Added this use statement

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
}

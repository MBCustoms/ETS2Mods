@extends('layouts.admin')

@section('header', 'Edit User: ' . $user->name)

@section('content')
<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul role="list" class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">User Information</h3>
                    
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Avatar -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                                <div class="mt-1 flex items-center space-x-5">
                                    <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @endif
                                    </span>
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                </div>
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <!-- Bio -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                <textarea name="bio" id="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">{{ old('bio', $user->bio) }}</textarea>
                            </div>

                            <!-- Status Flags -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified', $user->is_verified) ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <label for="is_verified" class="ml-2 block text-sm text-gray-900">Verified</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_banned" id="is_banned" value="1" {{ old('is_banned', $user->is_banned) ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <label for="is_banned" class="ml-2 block text-sm text-gray-900">Banned</label>
                                </div>
                            </div>

                            <!-- Warning Count -->
                            <div>
                                <label for="warning_count" class="block text-sm font-medium text-gray-700">Warning Count</label>
                                <input type="number" name="warning_count" id="warning_count" value="{{ old('warning_count', $user->warning_count ?? 0) }}" min="0" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                            </div>

                            <!-- Roles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                                <div class="space-y-2">
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">{{ ucfirst($role->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Change Password</h4>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Leave empty to keep current password">
                                </div>
                                <div class="mt-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="space-y-6">
            <!-- User Info Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <div class="mx-auto h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center mb-4">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400 mt-1">Joined {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Total Mods</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $stats['total_mods'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Approved</dt>
                        <dd class="text-sm font-medium text-green-600">{{ $stats['approved_mods'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Pending</dt>
                        <dd class="text-sm font-medium text-yellow-600">{{ $stats['pending_mods'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Rejected</dt>
                        <dd class="text-sm font-medium text-red-600">{{ $stats['rejected_mods'] }}</dd>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Total Downloads</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_downloads']) }}</dd>
                        </div>
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Total Views</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_views']) }}</dd>
                        </div>
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Total Comments</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_comments']) }}</dd>
                        </div>
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Total Ratings</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_ratings']) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Avg Rating</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['avg_rating'], 1) }} ⭐</dd>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Reports Made</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_reports']) }}</dd>
                        </div>
                        <div class="flex justify-between mb-2">
                            <dt class="text-sm text-gray-500">Following</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_following']) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Followers</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ number_format($stats['total_followers']) }}</dd>
                        </div>
                    </div>
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('users.show', $user) }}" target="_blank" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        View Public Profile
                    </a>
                    <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            {{ $user->is_verified ? 'Unverify' : 'Verify' }} User
                        </button>
                    </form>
                    <form action="{{ route('admin.users.shadow-ban', $user) }}" method="POST" class="inline-block w-full" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            {{ $user->shadow_banned_at ? 'Remove Shadow Ban' : 'Shadow Ban' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


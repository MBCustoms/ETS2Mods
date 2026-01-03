@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Profile</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Update your profile information and avatar.</p>
                </div>
                
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 p-4 rounded-md">
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

                <form class="mt-5 space-y-6" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Avatar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Avatar</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img id="avatar-preview" class="h-20 w-20 rounded-full object-cover" src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}" alt="{{ $user->name }}">
                                </div>
                                <div class="flex-1">
                                    <label for="avatar" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Change Avatar
                                    </label>
                                    <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <!-- Email (readonly) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 sm:text-sm" value="{{ $user->email }}" readonly>
                                <p class="mt-2 text-sm text-gray-500">Email cannot be changed.</p>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-900">Bio</label>
                            <div class="mt-1">
                                <textarea id="bio" name="bio" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 flex justify-end gap-x-3">
                        <a href="{{ route('users.show', $user) }}" class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection


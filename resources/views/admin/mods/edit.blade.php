@extends('layouts.admin')

@section('header', 'Edit Mod: ' . $mod->title)

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="border-t border-gray-200 p-6">
        <form action="{{ route('admin.mods.update', $mod) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Title -->
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title', $mod->title) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>

                <!-- Category -->
                <div class="sm:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <div class="mt-1">
                        <select id="category_id" name="category_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ $mod->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="10" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">{{ old('description', $mod->description) }}</textarea>
                    </div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select id="status" name="status" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2" onchange="toggleRejectionReason(this.value)">
                            <option value="pending" {{ $mod->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $mod->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $mod->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Rejection Reason -->
                <div class="sm:col-span-6" id="rejection_container" style="{{ $mod->status == 'rejected' ? '' : 'display:none;' }}">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                    <div class="mt-1">
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">{{ old('rejection_reason', $mod->rejection_reason) }}</textarea>
                    </div>
                </div>
                
                 <!-- Featured -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ $mod->is_featured ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-gray-700">Featured Mod</label>
                            <p class="text-gray-500">Enable to show this mod on the homepage featured section.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.mods.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Mod
                </button>
            </div>
        </form>
    </div>
</div>

<div class="hidden sm:block" aria-hidden="true">
    <div class="py-5">
        <div class="border-t border-gray-200"></div>
    </div>
</div>

<!-- Mod Images -->
<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="border-t border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Mod Images</h3>
        @php
            $images = $mod->modImages;
        @endphp
        @if($images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($images as $image)
                    <div class="relative group">
                        <img src="{{ $image->url }}" alt="Mod Image" class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_main ? 'border-indigo-500' : 'border-gray-300' }}">
                        @if($image->is_main)
                            <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">Main</span>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                            <a href="{{ $image->url }}" target="_blank" class="text-white opacity-0 group-hover:opacity-100 transition-opacity mr-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No images uploaded for this mod.</p>
        @endif
    </div>
</div>

<!-- Version Manager -->
@livewire('admin.mod-version-manager', ['mod' => $mod])

<script>
    function toggleRejectionReason(status) {
        const container = document.getElementById('rejection_container');
        if (status === 'rejected') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }
</script>
@endsection

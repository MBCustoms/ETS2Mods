@extends('layouts.app')

@section('title', (isset($category) && is_object($category)) ? $category->name . ' Mods' : 'All Mods')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Sidebar / Filters -->
            <div class="lg:w-1/4 mb-8 lg:mb-0">
                <!-- Categories Section - Collapsible on Mobile -->
                <div class="bg-white rounded-lg shadow overflow-hidden" x-data="{ categoriesOpen: window.innerWidth >= 1024 }">
                    <!-- Header - Clickable on Mobile -->
                    <button @click="categoriesOpen = !categoriesOpen" 
                            class="w-full px-6 py-4 flex items-center justify-between lg:cursor-default lg:pointer-events-none"
                            :class="{ 'border-b border-gray-200': categoriesOpen }">
                        <h3 class="text-lg font-medium text-gray-900">Categories</h3>
                        <svg class="h-5 w-5 text-gray-400 transition-transform lg:hidden" 
                             :class="{ 'rotate-180': categoriesOpen }"
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Categories List -->
                    <div x-show="categoriesOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="p-6 pt-4">
                        @php
                            $selectedCategoryId = null;
                            if (isset($category)) {
                                $selectedCategoryId = is_object($category) ? $category->id : $category;
                            }
                        @endphp
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md transition {{ !$selectedCategoryId ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                                    All Categories
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('categories.show', $cat) }}" class="block px-3 py-2 rounded-md transition {{ ($selectedCategoryId && (string)$selectedCategoryId === (string)$cat->id) ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Filters Section - Collapsible on Mobile -->
                <div class="bg-white rounded-lg shadow overflow-hidden mt-6" x-data="{ filtersOpen: window.innerWidth >= 1024 }">
                    <!-- Header - Clickable on Mobile Only -->
                    <button @click="filtersOpen = !filtersOpen" 
                            type="button"
                            class="w-full px-6 py-4 flex items-center justify-between lg:pointer-events-none"
                            :class="{ 'border-b border-gray-200': filtersOpen }">
                        <h3 class="text-lg font-medium text-gray-900">Filters</h3>
                        <svg class="h-5 w-5 text-gray-400 transition-transform lg:hidden" 
                             :class="{ 'rotate-180': filtersOpen }"
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Filters Form -->
                    <div x-show="filtersOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="p-6 pt-4">
                        <form action="{{ route('mods.search') }}" method="GET">
                            <div class="mb-4">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Search mods..."
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2.5">
                            </div>

                            <div class="mb-4">
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select id="category" 
                                        name="category" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2.5">
                                    <option value="">Any Category</option>
                                    @foreach($categories as $catOption)
                                        <option value="{{ $catOption->id }}" {{ (string)request('category') === (string)$catOption->id ? 'selected' : '' }}>{{ $catOption->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="game_version" class="block text-sm font-medium text-gray-700 mb-2">Game Version</label>
                                <input type="text" 
                                       name="game_version" 
                                       id="game_version" 
                                       value="{{ request('game_version') }}" 
                                       placeholder="e.g. 1.50" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border p-2.5">
                            </div>

                            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2.5 rounded-md hover:bg-orange-700 transition font-medium shadow-sm hover:shadow">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Ad Sidebar -->
                <div class="mt-6">
                    <x-ad-slot slotName="category_sidebar" />
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ isset($category) ? $category->name : 'All Mods' }}</h1>
                    @if(isset($category) && is_object($category) && $category->description)
                        <p class="text-gray-600 mt-3 leading-relaxed">{{ $category->description }}</p>
                    @endif
                    @if(request('search'))
                        <p class="text-gray-500 mt-2">Showing results for "{{ request('search') }}"</p>
                    @endif
                </div>

                @if($mods->count() > 0)
                    <!-- Responsive Grid: 1 col mobile, 2 col tablet, 3 col desktop, 4 col XL -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                        @foreach($mods as $index => $mod)
                            <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white hover:shadow-xl transition-shadow duration-200">
                                <div class="flex-shrink-0 relative aspect-video bg-gray-200">
                                    <!-- Lazy load all images except first 4 (above the fold) -->
                                    <img class="h-full w-full object-cover" 
                                         src="{{ $mod->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" 
                                         alt="{{ $mod->title }}"
                                         {{ $index >= 4 ? 'loading=lazy' : 'loading=eager' }}
                                         width="600"
                                         height="400">
                                    <div class="absolute top-2 right-2 flex flex-col items-end gap-1">
                                        <span class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                            {{ $mod->latestVersion?->game_version ?? 'Any' }}
                                        </span>
                                        @if($mod->is_featured)
                                            <span class="bg-orange-600 text-white px-2 py-1 rounded text-xs font-semibold shadow-sm">
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-1 flex-col justify-between p-6">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-orange-600">
                                            <a href="{{ route('categories.show', $mod->category) }}" class="hover:underline">
                                                {{ $mod->category->name }}
                                            </a>
                                        </p>
                                        <a href="{{ route('mods.show', $mod) }}" class="mt-2 block">
                                            <p class="text-xl font-semibold text-gray-900">{{ $mod->title }}</p>
                                            <p class="mt-3 text-base text-gray-500 line-clamp-3">
                                                {!! Str::limit($mod->description, 100) !!}
                                            </p>
                                        </a>
                                    </div>
                                    <div class="mt-6 flex items-center justify-between">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <div class="flex items-center mr-3">
                                                @if($mod->user->avatar_url)
                                                    <img src="{{ $mod->user->avatar_url }}" alt="{{ $mod->user->name }}" class="h-8 w-8 rounded-full object-cover mr-2">
                                                @else
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">{{ substr($mod->user->name,0,1) }}</span>
                                                @endif
                                                <a href="{{ route('users.show', $mod->user) }}" class="text-sm text-gray-700 hover:text-orange-600">{{ $mod->user->name }}</a>
                                            </div>
                                            <div class="flex items-center mr-4">
                                                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                                {{ number_format($mod->views_count) }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                                {{ number_format($mod->downloads_count) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if(($index + 1) % 6 == 0 && $index < $mods->count() - 1)
                                <div class="col-span-full my-4">
                                    <x-ad-slot slotName="category_inline" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $mods->links() }}
                    </div>
                @else
                    <div class="bg-white p-12 text-center rounded-lg shadow text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No mods found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<div class="py-12">
    <div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4 mb-6 lg:mb-0">
                <div class="bg-white p-6 rounded-lg shadow-sm sticky top-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                         <svg class="w-5 h-5 mr-2 text-primary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filters
                    </h2>
                    
                    <!-- Search Input -->
                    <div class="mb-6">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text" id="search" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-orange focus:ring-primary-orange sm:text-sm pl-10 h-10" placeholder="Search mods...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Category</h3>
                        <ul class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                            <li>
                                <a href="{{ route('mods.index') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm {{ !$category ? 'bg-primary-orange text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                    All Categories
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('categories.show', $cat) }}" wire:navigate class="block px-3 py-2 rounded-md text-sm {{ $category == $cat->id ? 'bg-primary-orange text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Version Filter -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Game Version</h3>
                        <select wire:model.live="version" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-orange focus:ring-primary-orange sm:text-sm">
                            <option value="">Any Version</option>
                            @foreach($filter_versions as $v)
                                <option value="{{ $v }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Sorting -->
                    <div>
                         <h3 class="text-sm font-medium text-gray-700 mb-2">Sort By</h3>
                         <select wire:model.live="sort" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-orange focus:ring-primary-orange sm:text-sm">
                            <option value="newest">Newest First</option>
                            <option value="popular">Most Popular</option>
                            <option value="top_rated">Highest Rated</option>
                            <option value="relevance">Relevance</option>
                        </select>
                    </div>
                </div>
                
                <x-ad-slot name="sidebar" />
            </div>

            <!-- Mod Grid -->
            <div class="lg:w-3/4">
                <div class="mb-4 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $search ? 'Search Results' : 'All Mods' }}
                        <span class="text-sm font-normal text-gray-500 ml-2">({{ $mods->total() }} found)</span>
                    </h1>
                </div>

                @if($mods->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mods as $mod)
                            <a href="{{ route('mods.show', $mod) }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col h-full border border-gray-100 group">
                                <div class="relative aspect-w-16 aspect-h-9 bg-gray-200 overflow-hidden">
                                     <img src="{{ $mod->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $mod->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500">
                                     <div class="absolute top-2 right-2 flex space-x-1">
                                         @if($mod->rating_avg_rating >= 4.5)
                                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded shadow">Featured</span>
                                         @endif
                                     </div>
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                            {{ $mod->title }}
                                        </h3>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs mr-2">
                                                {{ $mod->category->name }}
                                            </span>
                                            <span>v{{ $mod->latestVersion?->game_version ?? $mod->game_version ?? 'N/A' }}</span>
                                        </div>
                                        
                                        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="h-4 w-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                {{ number_format($mod->reviews_avg ?? 0, 1) }}
                                                <span class="text-xs ml-1">({{ $mod->reviews_count ?? 0 }})</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="flex items-center mr-3">
                                                    @if($mod->user->avatar_url)
                                                        <img src="{{ $mod->user->avatar_url }}" alt="{{ $mod->user->name }}" class="h-6 w-6 rounded-full object-cover mr-2">
                                                    @else
                                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">{{ substr($mod->user->name,0,1) }}</span>
                                                    @endif
                                                    <span class="text-xs text-gray-700">{{ $mod->user->name }}</span>
                                                </div>
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                {{ number_format($mod->downloads_count) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $mods->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

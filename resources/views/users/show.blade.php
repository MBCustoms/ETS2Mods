@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <div class="lg:w-3/4">
        
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-primary-orange">
            <div class="md:flex">
                <!-- Left Sidebar (Avatar & Bio) -->
                <div class="md:w-1/3 bg-gray-50 p-8 text-center border-r border-gray-100">
                    <div class="relative inline-block">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover ring-4 ring-white shadow-sm">
                        @else
                            <span class="inline-flex items-center justify-center h-32 w-32 rounded-full bg-gray-300 ring-4 ring-white shadow-sm text-4xl font-bold text-gray-500">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        @endif
                        @if($user->is_verified)
                        <span class="absolute bottom-1 right-1 bg-blue-500 p-1.5 rounded-full border-2 border-white text-white" title="Verified Author">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                        @endif
                    </div>
                    
                    <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">Joined {{ $user->created_at->format('M Y') }}</p>

                    <!-- Badges -->
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        @foreach($badges as $badge)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $badge['color'] ?? 'gray' }}-100 text-{{ $badge['color'] ?? 'gray' }}-800 border border-{{ $badge['color'] ?? 'gray' }}-200">
                                {{ $badge['name'] }}
                            </span>
                        @endforeach
                    </div>
                    
                    @if($user->bio)
                        <div class="mt-6 text-sm text-gray-600 italic">
                            "{{ $user->bio }}"
                        </div>
                    @endif
                    
                    <!-- Social Links -->
                    @if(!empty($user->social_links))
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Social Media</h3>
                            <div class="flex justify-center flex-wrap gap-3">
                                @if(!empty($user->social_links['facebook']))
                                    <a href="{{ $user->social_links['facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-600 transition" title="Facebook">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($user->social_links['twitter']))
                                    <a href="{{ $user->social_links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-400 transition" title="Twitter / X">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($user->social_links['instagram']))
                                    <a href="{{ $user->social_links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-pink-600 transition" title="Instagram">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($user->social_links['youtube']))
                                    <a href="{{ $user->social_links['youtube'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-red-600 transition" title="YouTube">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                @endif
                                @if(!empty($user->social_links['website']))
                                    <a href="{{ $user->social_links['website'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-primary-orange transition" title="Website">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Content (Stats & Mods) -->
                <div class="md:w-2/3 p-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-4 mb-8 text-center divide-x divide-gray-200">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Total Downloads</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['mods_count'] }}</div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Mods Uploaded</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_rating'], 1) }} <span class="text-yellow-400 text-lg">★</span></div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Avg Rating</div>
                        </div>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Published Mods</h2>
                    
                    @if($mods->count() > 0)
                        <div class="space-y-4">
                            @foreach($mods as $mod)
                                <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition border border-gray-100">
                                    <img src="{{ $mod->first_image_url ?: 'https://placehold.co/100x100?text=Mod' }}" class="w-16 h-16 rounded-md object-cover bg-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-bold text-gray-900 truncate">
                                            <a href="{{ route('mods.show', $mod) }}" class="hover:underline">{{ $mod->title }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($mod->description), 80) }}</p>
                                        <div class="flex items-center mt-2 text-xs text-gray-400 space-x-3">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                {{ number_format($mod->downloads_count) }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                {{ number_format($mod->reviews_avg ?? 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                         <a href="{{ route('mods.download', $mod) }}" class="text-xs font-medium text-primary-orange hover:text-primary-orange-dark border border-primary-orange px-3 py-1 rounded-full hover:bg-orange-50 transition">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $mods->links() }}
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            <p>No mods published yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="profile_sidebar" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'My Followings')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">My Followings</h1>
                    <p class="text-gray-500 mt-2">Mods and users you're following</p>
                </div>

                @if($followings->count() > 0)
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($followings as $following)
                            @php
                                $item = $following->followable;
                            @endphp
                            @if($item)
                                @if($item instanceof \App\Models\Mod)
                                    <!-- Mod Card -->
                                    <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white">
                                        <div class="flex-shrink-0 relative">
                                            <img class="h-48 w-full object-cover" src="{{ $item->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $item->title }}">
                                            <div class="absolute top-2 left-2 bg-orange-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                                Mod
                                            </div>
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between p-6">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-orange-600">
                                                    {{ $item->category->name }}
                                                </p>
                                                <a href="{{ route('mods.show', $item) }}" class="mt-2 block">
                                                    <p class="text-xl font-semibold text-gray-900">{{ $item->title }}</p>
                                                    <p class="mt-3 text-base text-gray-500 line-clamp-2">
                                                        {{ Str::limit(strip_tags($item->description), 80) }}
                                                    </p>
                                                </a>
                                            </div>
                                            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ number_format($item->views_count) }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ number_format($item->downloads_count) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item instanceof \App\Models\User)
                                    <!-- User Card -->
                                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-t-4 border-primary-orange">
                                        <div class="p-6 text-center">
                                            <div class="relative inline-block">
                                                @if($item->avatar_url)
                                                    <img src="{{ $item->avatar_url }}" alt="{{ $item->name }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-white shadow-sm">
                                                @else
                                                    <span class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-gray-300 ring-4 ring-white shadow-sm text-3xl font-bold text-gray-500">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </span>
                                                @endif
                                                @if($item->is_verified)
                                                    <span class="absolute bottom-1 right-1 bg-blue-500 p-1.5 rounded-full border-2 border-white text-white" title="Verified Author">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $item->name }}</h3>
                                            <p class="text-sm text-gray-500">Joined {{ $item->created_at->format('M Y') }}</p>
                                            <div class="mt-4 flex justify-center space-x-4 text-sm text-gray-500">
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $item->mods()->approved()->count() }}</div>
                                                    <div>Mods</div>
                                                </div>
                                            </div>
                                            <a href="{{ route('users.show', $item) }}" class="mt-4 inline-block px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 text-sm font-medium">
                                                View Profile
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $followings->links() }}
                    </div>
                @else
                    <div class="bg-white p-12 text-center rounded-lg shadow text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">You're not following anything yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start following mods and users to see their updates here!</p>
                        <div class="mt-6">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                                Browse Mods
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="followings_sidebar" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


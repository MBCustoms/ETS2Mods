@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="bg-white pb-8 sm:pb-12 lg:pb-12">
    <!-- Hero Section -->
    <div class="pt-8 overflow-hidden sm:pt-12 lg:relative lg:py-48 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="mx-auto max-w-md px-4 sm:max-w-3xl sm:px-6 lg:px-8 lg:max-w-7xl lg:grid lg:grid-cols-2 lg:gap-24">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    The Best Mods for ETS2 & ATS
                </h1>
                <p class="mt-6 text-xl text-indigo-100 max-w-3xl">
                    Discover thousands of high-quality mods for Euro Truck Simulator 2 and American Truck Simulator. Trucks, maps, sounds, and more.
                </p>
                <div class="mt-10 sm:flex">
                    <div class="mt-3 sm:mt-0 sm:ml-3">
                         <a href="{{ route('mods.index') }}" class="flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                            Browse Mods
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <div class="lg:w-3/4">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Featured Mods</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($featured as $index => $mod)
                        <div class="group relative">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                 <img src="{{ $mod->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $mod->title }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('mods.show', $mod) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $mod->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $mod->category->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(($index + 1) % 4 == 0 && $index < $featured->count() - 1)
                            <div class="col-span-full my-4">
                                <x-ad-slot slotName="home_inline" />
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-16 border-t border-gray-200 pt-10">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Recent Uploads</h2>
                    <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                        @foreach($latest as $index => $mod)
                            <div class="flex flex-col overflow-hidden rounded-lg shadow-lg">
                                <div class="flex-shrink-0">
                                     <img class="h-48 w-full object-cover" src="{{ $mod->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="">
                                </div>
                                <div class="flex flex-1 flex-col justify-between bg-white p-6">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600">
                                            <a href="{{ route('categories.show', $mod->category) }}" class="hover:underline">{{ $mod->category->name }}</a>
                                        </p>
                                        <a href="{{ route('mods.show', $mod) }}" class="mt-2 block">
                                            <p class="text-xl font-semibold text-gray-900">{{ $mod->title }}</p>
                                            <p class="mt-3 text-base text-gray-500 line-clamp-3">{{ Str::limit($mod->description, 100) }}</p>
                                        </a>
                                    </div>
                                   <div class="mt-6 flex items-center">
                                        <div class="ml-3 flex items-center">
                                            @if($mod->user->avatar_url)
                                                <img src="{{ $mod->user->avatar_url }}" alt="{{ $mod->user->name }}" class="h-8 w-8 rounded-full object-cover mr-2">
                                            @else
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">{{ substr($mod->user->name,0,1) }}</span>
                                            @endif
                                            <div>
                                                <a href="{{ route('users.show', $mod->user) }}" class="text-sm font-medium text-gray-900 hover:text-orange-600">{{ $mod->user->name }}</a>
                                                <div class="flex space-x-1 text-sm text-gray-500"><time datetime="{{ $mod->created_at->toIso8601String() }}">{{ $mod->created_at->diffForHumans() }}</time></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if(($index + 1) % 6 == 0 && $index < $latest->count() - 1)
                                <div class="col-span-full my-4">
                                    <x-ad-slot slotName="home_inline" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-10 text-center">
                        <a href="{{ route('mods.index') }}" class="text-indigo-600 font-semibold hover:text-indigo-900">View all mods &rarr;</a>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="home_sidebar" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

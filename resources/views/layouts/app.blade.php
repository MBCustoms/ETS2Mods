<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') - {{ setting('site.name', config('app.name', 'ETS2LT')) }}</title>
    <meta name="description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('seo.meta_keywords'))">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', setting('site.name')) - {{ setting('site.name', config('app.name', 'ETS2LT')) }}">
    <meta property="og:description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta property="og:image" content="@yield('meta_image', asset(setting('site.logo', '/images/logo.png')))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', setting('site.name')) - {{ setting('site.name', config('app.name', 'ETS2LT')) }}">
    <meta property="twitter:description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta property="twitter:image" content="@yield('meta_image', asset(setting('site.logo', '/images/logo.png')))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    @if(asset(setting('site.favicon', '/images/favicon.png')))
        <link rel="icon" type="image/png" href="{{ asset(setting('site.favicon', '/images/favicon.png')) }}">
        <link rel="shortcut icon" href="{{ asset(setting('site.favicon', '/images/favicon.png')) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    {!! NoCaptcha::renderJs() !!}
    
    <!-- AdSense Script (conditional, loaded once) -->
    @include('includes.adsense-script')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="font-bold text-xl text-primary-orange tracking-tighter flex items-center">
                                {{ setting('site.name', config('app.name', 'ETS2LT')) }}
                            </a>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div class="hidden sm:flex sm:ml-10 sm:items-center space-x-2">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                Home
                            </a>
                            <a href="{{ route('contact.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('contact.index') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                Contact
                            </a>
                            @isset($activePages)
                                @foreach($activePages as $page)
                                    <a href="{{ route('pages.show', $page->slug) }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pages.show') && request()->route('slug') === $page->slug ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                        {{ $page->title }}
                                    </a>
                                @endforeach
                            @endisset
                        </div>
                    </div>

                    <!-- Desktop Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <a href="{{ auth()->check() ? route('mods.create') : route('login') }}"
                            class="inline-flex items-center px-4 py-2 {{ auth()->check() ? 'mr-2' : 'mr-5' }} rounded-lg font-semibold text-sm
                            text-white bg-gradient-to-r from-orange-500 to-orange-600
                            hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg
                            transition transform hover:scale-[1.03]">
                            Submit Mod
                        </a>
                        @auth
                            <div class="ml-3 relative flex items-center space-x-4">                                
                                <!-- User Dropdown -->
                                <div class="relative ml-3" x-data="{ open: false }">
                                    <div>
                                        <button @click="open = !open" type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Open user menu</span>
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}" alt="{{ auth()->user()->name }}" loading="lazy">
                                        </button>
                                    </div>
                                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                        <div class="flex items-center px-4 py-2 border-b border-gray-200">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full object-cover ring-2 ring-orange-500 ring-offset-2" 
                                                    src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=EA580C&color=fff' }}" 
                                                    alt="{{ auth()->user()->name }}" 
                                                    loading="lazy">
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                        @if(auth()->user()->hasRole(['admin', 'moderator']))
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-1 text-base text-purple-700 bg-purple-50 hover:bg-purple-100 transition" role="menuitem">
                                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                Admin Panel
                                            </a>
                                        @endif
                                        <a href="{{ route('users.show', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Profile</a>
                                        <a href="{{ route('users.edit', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit Profile</a>
                                        <a href="{{ route('mods.my-mods') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Mods</a>
                                        <a href="{{ route('users.followings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Followings</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 transition" role="menuitem">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
                                <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500 transition" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()" aria-label="Toggle navigation menu">
                            <span class="sr-only">Open main menu</span>
                            <!-- Icon when menu is closed -->
                            <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icon when menu is open -->
                            <svg x-show="mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-1" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 @click.away="mobileMenuOpen = false"
                 class="sm:hidden border-t border-gray-200 bg-white shadow-lg" 
                 id="mobile-menu"
                 style="display: none;">
                
                <!-- Main Navigation Links -->
                <div class="px-4 pt-4 pb-3 space-y-1">
                    <a href="{{ route('home') }}" 
                       class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('home') ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('contact.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('contact.index') ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}"
                       @click="mobileMenuOpen = false">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact
                    </a>
                    @isset($activePages)
                        @foreach($activePages as $page)
                            <a href="{{ route('pages.show', $page->slug) }}" 
                               class="flex items-center px-4 py-3 rounded-lg text-base font-medium transition {{ request()->routeIs('pages.show') && request()->route('slug') === $page->slug ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ $page->title }}
                            </a>
                        @endforeach
                    @endisset
                </div>

                <!-- User Section -->
                @auth
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <!-- User Info Card -->
                        <div class="flex items-center px-4 mb-3 pb-3 border-b border-gray-100">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full object-cover ring-2 ring-orange-500 ring-offset-2" 
                                     src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=EA580C&color=fff' }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     loading="lazy">
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-base font-semibold text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="px-4 space-y-2">
                            <a href="{{ route('mods.create') }}" 
                               class="flex items-center justify-center px-4 py-3 rounded-lg text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition transform hover:scale-[1.02]"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Submit Mod
                            </a>
                            
                            <a href="{{ route('users.show', auth()->user()) }}" 
                               class="flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 transition"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                My Profile
                            </a>
                            
                            <a href="{{ route('users.edit', auth()->user()) }}" 
                               class="flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 transition"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            
                            <a href="{{ route('mods.my-mods') }}" 
                               class="flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 transition"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                My Mods
                            </a>
                            
                            <a href="{{ route('users.followings') }}" 
                               class="flex items-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-50 transition"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Followings
                            </a>
                            
                            @if(auth()->user()->hasRole(['admin', 'moderator']))
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center px-4 py-3 rounded-lg text-base font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition"
                                   @click="mobileMenuOpen = false">
                                    <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Admin Panel
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center px-4 py-3 rounded-lg text-base font-medium text-red-700 hover:bg-red-50 transition">
                                    <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="px-4 space-y-2">
                            <a href="{{ route('login') }}" 
                               class="flex items-center justify-center px-4 py-3 rounded-lg text-base font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 transition"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Log in
                            </a>
                            <a href="{{ route('register') }}" 
                               class="flex items-center justify-center px-4 py-3 rounded-lg text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-md hover:shadow-lg transition transform hover:scale-[1.02]"
                               @click="mobileMenuOpen = false">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Register
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            <!-- Email Verification Warning -->
            @if(auth()->check() && !auth()->user()->hasVerifiedEmail())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Your email address is not verified. Please check your email for a verification link.
                                <a href="{{ route('verification.notice') }}" class="font-medium underline hover:text-yellow-600">Resend Link</a>
                            </p>
                            @if(session('message') == 'Verification link sent!')
                                <p class="mt-2 text-sm text-green-600 font-medium">A new verification link has been sent to your email address.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Toast Notifications -->
            <div class="fixed top-20 right-5 z-50 flex flex-col space-y-4 w-full max-w-sm pointer-events-none">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-green-500 shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-white">{{ session('success') }}</p>
                                </div>
                                <div class="ml-4 flex flex-shrink-0">
                                    <button type="button" @click="show = false" class="inline-flex rounded-md bg-green-500 text-white hover:text-green-100 focus:outline-none focus:ring-2 focus:ring-white">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-red-500 shadow-lg ring-1 ring-black ring-opacity-5">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-white">{{ session('error') }}</p>
                                </div>
                                <div class="ml-4 flex flex-shrink-0">
                                    <button type="button" @click="show = false" class="inline-flex rounded-md bg-red-500 text-white hover:text-red-100 focus:outline-none focus:ring-2 focus:ring-white">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} {{ setting('site.name', config('app.name', 'ETS2LT')) }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
    
    @livewireScripts
    @stack('scripts')

    
    <!-- Cookie Consent Banner -->
    <x-cookie-consent />
</body>
</html>

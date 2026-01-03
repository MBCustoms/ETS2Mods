<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ETS2LT') }} - @yield('title', setting('site.name', 'Mods'))</title>
    <meta name="description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('seo.meta_keywords'))">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', setting('site.name')) - {{ config('app.name', 'ETS2LT') }}">
    <meta property="og:description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta property="og:image" content="@yield('meta_image', asset(setting('site.logo', '/images/logo.png')))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', setting('site.name')) - {{ config('app.name', 'ETS2LT') }}">
    <meta property="twitter:description" content="@yield('meta_description', setting('seo.meta_description'))">
    <meta property="twitter:image" content="@yield('meta_image', asset(setting('site.logo', '/images/logo.png')))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="font-bold text-xl text-primary-orange tracking-tighter">
                                ETS2<span class="text-gray-900">LT</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                Home
                            </a>
                            <a href="{{ route('mods.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('mods.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                Mods
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Language Dropdown -->
                        <div class="relative ml-3 mr-4" x-data="{ open: false }">
                            <div>
                                <button @click="open = ! open" type="button" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none transition duration-150 ease-in-out">
                                    <span class="text-gray-500 hover:text-gray-700 uppercase font-bold">{{ app()->getLocale() }}</span>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" style="display: none;" class="origin-top-right absolute right-0 mt-2 w-24 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                                <a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                                <a href="{{ route('lang.switch', 'de') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Deutsch</a>
                                <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
                            </div>
                        </div>

                        @auth
                            <div class="ml-3 relative flex items-center space-x-4">
                                <a href="{{ route('mods.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Submit Mod
                                </a>
                                
                                <!-- User Dropdown -->
                                <div class="relative ml-3" x-data="{ open: false }">
                                    <div>
                                        <button @click="open = !open" type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                                            <span class="sr-only">Open user menu</span>
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}" alt="{{ auth()->user()->name }}">
                                        </button>
                                    </div>
                                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                        <div class="px-4 py-2 border-b border-gray-200">
                                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                        <a href="{{ route('users.show', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Profile</a>
                                        <a href="{{ route('users.edit', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit Profile</a>
                                        @if(auth()->user()->hasRole(['admin', 'moderator']))
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Admin Panel</a>
                                        @endif
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
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
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} ETS2LT. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
    
    @livewireScripts
</body>
</html>

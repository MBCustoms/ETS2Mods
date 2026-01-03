<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'ETS2LT') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-surface-dark">
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-900">
                    <span class="text-xl font-bold text-primary-orange">ETS2LT Admin</span>
                </div>
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-2 py-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white border-l-4 border-primary-orange' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('admin.mods.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.mods.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                             Mods
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Categories
                        </a>

                <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reports.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    Reports
                </a>

                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Users
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Settings
                    </a>
                    
                    <a href="{{ route('admin.activity-log.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.activity-log.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Activity Log
                    </a>
                @endif
            </nav>
                </div>
            </div>
        </div>

        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <div class="bg-white shadow-sm h-16 flex items-center justify-between px-4">
                <h1 class="text-xl font-semibold text-gray-800">
                    @yield('header')
                </h1>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if(session('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('message') }}</p>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
    
    @livewireScripts
</body>
</html>

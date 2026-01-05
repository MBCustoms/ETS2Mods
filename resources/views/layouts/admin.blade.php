<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') - {{ setting('site.name', config('app.name', 'ETS2LT')) }}</title>

    <!-- Favicon -->
    @if(asset(setting('site.favicon', '/images/favicon.png')))
        <link rel="icon" type="image/png" href="{{ asset(setting('site.favicon', '/images/favicon.png')) }}">
        <link rel="shortcut icon" href="{{ asset(setting('site.favicon', '/images/favicon.png')) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-gray-900 border-r border-gray-800">
                <!-- Logo/Brand -->
                <div class="flex items-center h-16 flex-shrink-0 px-6 bg-gradient-to-r from-gray-900 to-gray-800 border-b border-gray-800">
                    <div class="flex items-center space-x-2">
                            @if(!empty($appLogo))
                                <img src="{{ asset($appLogo) }}" alt="{{ $appName }}" class="h-8 w-auto">
                            @else
                                <svg class="h-8 w-8 text-primary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            @endif
                        <span class="text-xl font-bold text-white">{{ $appName }}</span>
                        <span class="text-xs font-medium text-primary-orange bg-orange-900/30 px-2 py-0.5 rounded">Admin</span>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-3 py-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.mods.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.mods.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Mods
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Categories
                        </a>

                        @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'moderator']))

                            <a href="{{ route('admin.pages.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Pages
                            </a>

                            <div class="pt-2 mt-2 border-t border-gray-800">
                                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Content</p>
                            </div>

                            <a href="{{ route('admin.contacts.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.contact.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Messages
                            </a>

                            <a href="{{ route('admin.analytics.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path>
                                </svg>
                                Analytics
                            </a>

                            <div class="pt-4 mt-4 border-t border-gray-800">
                                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Administration</p>
                            </div>

                            <a href="{{ route('admin.reports.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.index') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Reports
                            </a>

                            <a href="{{ route('admin.comments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.comments.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Comments
                                @php
                                    $pendingCommentsCount = \App\Models\ModComment::where('is_approved', false)->count();
                                @endphp
                                @if($pendingCommentsCount > 0)
                                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-500 text-white">
                                        {{ $pendingCommentsCount }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Users
                            </a>
                            
                            <a href="{{ route('admin.activity-log.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.activity-log.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Activity Log
                            </a>

                            <a href="{{ route('admin.sitemap.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.sitemap.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                Sitemap
                            </a>

                            <a href="{{ route('admin.settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings
                            </a>

                            <a href="{{ route('admin.ad-settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.ad-settings.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                </svg>
                                Ad Settings
                            </a>

                            <a href="{{ route('admin.email-settings.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.email-settings.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email Settings
                            </a>
                        @endif
                    </nav>
                    
                    <!-- User Section at Bottom -->
                    <div class="px-3 py-4 border-t border-gray-800">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-orange-600 flex items-center justify-center">
                                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=EA580C&color=fff' }}"  alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-full border border-orange-600 border-2">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:pl-64 flex flex-col flex-1">
            <!-- Top bar -->
            <div class="bg-white shadow-md border-b border-gray-200 h-16 flex items-center justify-between px-6">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-gray-900">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-gray-600 hover:text-orange-600 transition-colors flex items-center space-x-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>View Site</span>
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors rounded-md hover:bg-red-50">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirm-action', (event) => {
                Swal.fire({
                    title: event.title || 'Are you sure?',
                    text: event.text || "You won't be able to revert this!",
                    icon: event.icon || 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c', // Orange-600
                    cancelButtonColor: '#d33',
                    confirmButtonText: event.confirmButtonText || 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let params = event.params;
                        if (!Array.isArray(params)) {
                            params = [params];
                        }
                        Livewire.dispatch(event.method, params);
                    }
                });
            });
        });

        // Override native confirm for non-livewire or simple wire:confirm replacements if needed, 
        // but simpler is to use a custom action. 
        // For wire:confirm, we can't easily intercept it globally cleanly without dirty hacks.
        // Instead, we will recommend replacing wire:confirm with a custom Alpine/JS handler.
        
        // Helper function for standard onclick confirm replacement
        function confirmSAction(formId, title, text, icon) {
             Swal.fire({
                title: title || 'Are you sure?',
                text: text || "You won't be able to revert this!",
                icon: icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
            return false;
        }
    </script>
    @stack('scripts')
</body>
</html>

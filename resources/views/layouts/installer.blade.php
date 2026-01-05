<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETS2Mods Installer - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <style>
        .step-active { @apply border-orange-500 text-orange-600; }
        .step-completed { @apply border-green-500 text-green-600; }
        .step-inactive { @apply border-gray-200 text-gray-400; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans text-gray-900">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                <span class="block text-orange-600">ETS2Mods</span>
                <span class="block text-2xl mt-1 text-gray-600">Installation Wizard</span>
            </h1>
        </div>

        <!-- Steps -->
        <div class="mb-8">
            <nav aria-label="Progress">
                <ol role="list" class="flex items-center justify-center">
                    @php
                        $steps = [
                            'welcome' => 'Welcome',
                            'requirements' => 'Requirements',
                            'permissions' => 'Permissions',
                            'environment' => 'Environment',
                            'database' => 'Database',
                            'admin' => 'Admin Account',
                            'finish' => 'Finish'
                        ];
                        $currentRoute = Route::currentRouteName();
                        $currentStep = str_replace('install.', '', $currentRoute);
                        // Map special cases if needed, e.g. environment.save -> environment
                        if(str_contains($currentStep, '.')) $currentStep = explode('.', $currentStep)[0];
                        
                        $passed = false;
                    @endphp

                    @foreach($steps as $key => $label)
                        @php
                            $active = $key === $currentStep;
                            $completed = !$active && !$passed; 
                            if ($active) $passed = true;
                        @endphp
                        
                        <li class="relative {{ !$loop->last ? 'pr-8 sm:pr-20' : '' }}">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200"></div>
                            </div>
                            <a href="#" class="relative flex h-8 w-8 items-center justify-center rounded-full bg-white border-2 {{ $active ? 'border-orange-600' : ($completed ? 'border-green-500' : 'border-gray-300') }} hover:bg-gray-50">
                                @if($completed)
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($active)
                                    <span class="h-2.5 w-2.5 rounded-full bg-orange-600" aria-hidden="true"></span>
                                @else
                                    <span class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300" aria-hidden="true"></span>
                                @endif
                                <span class="absolute -bottom-8 w-max text-xs font-medium {{ $active ? 'text-orange-600' : 'text-gray-500' }}">{{ $label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Content -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-12">
            <div class="px-4 py-5 sm:p-6">
                @yield('content')
            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center">
                @yield('footer')
            </div>
        </div>
        
        <div class="text-center mt-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} TurkishMods Developer Team - ETS2Mods Installer.
        </div>
    </div>
</body>
</html>

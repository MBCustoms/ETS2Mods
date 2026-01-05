@extends('layouts.app')

@section('title', 'Downloading ' . $mod->title)

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
            
            <h2 class="text-2xl font-extrabold text-gray-900 mb-4">
                {{ $redirectText }}
            </h2>
            
            <div class="mb-6">
                <!-- Ad Unit -->
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center border border-gray-200 rounded">
                     <x-ad-slot slotName="download_redirect" />
                     <span class="text-gray-400 text-sm">Advertisement</span>
                </div>
            </div>

            <p class="text-gray-600 mb-6">
                Redirecting to <span class="font-semibold text-orange-600">{{ $linkName }}</span> in 
                <span id="countdown" class="font-bold text-gray-900">{{ $timer }}</span> seconds...
            </p>

            <a href="{{ $targetUrl }}" id="manual-link" class="hidden text-sm text-orange-600 hover:text-orange-500">
                Click here if you are not redirected automatically
            </a>

            <div class="mt-6">
                <a href="{{ route('mods.show', $mod) }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                    &larr; Back to Mod
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = {{ $timer }};
        const countdownEl = document.getElementById('countdown');
        const manualLink = document.getElementById('manual-link');
        const targetUrl = "{{ $targetUrl }}";
        
        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                manualLink.classList.remove('hidden');
                window.location.href = targetUrl;
            }
        }, 1000);
    });
</script>
@endsection

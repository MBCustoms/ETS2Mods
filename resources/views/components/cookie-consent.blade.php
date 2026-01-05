@php
    $cookieConsent = $_COOKIE['cookie_consent'] ?? null;
@endphp

@if(!$cookieConsent)
    <div id="cookie-consent-banner" 
         class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-lg z-50 border-t border-gray-700"
         x-data="{ show: true }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-y-full"
         x-transition:enter-end="transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="transform translate-y-0"
         x-transition:leave-end="transform translate-y-full">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm">
                    We use cookies to enhance your browsing experience and serve personalized ads. 
                    By continuing to use this site, you consent to our use of cookies.
                    <a href="{{ route('pages.show', 'cookie-policy') }}" class="underline hover:text-orange-400 ml-1">Learn more</a>
                </p>
            </div>
            <div class="flex gap-3">
                <button 
                    @click="
                        const date = new Date();
                        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
                        document.cookie = 'cookie_consent=accepted; path=/; expires=' + date.toUTCString() + '; SameSite=Lax';
                        localStorage.setItem('cookie_consent', 'accepted');
                        show = false;
                    "
                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition-colors">
                    Accept
                </button>
                <button 
                    @click="
                        const date = new Date();
                        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
                        document.cookie = 'cookie_consent=rejected; path=/; expires=' + date.toUTCString() + '; SameSite=Lax';
                        localStorage.setItem('cookie_consent', 'rejected');
                        show = false;
                    "
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                    Reject
                </button>
            </div>
        </div>
    </div>
@endif


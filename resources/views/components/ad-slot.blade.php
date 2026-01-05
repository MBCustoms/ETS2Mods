@props(['slotName'])

@php
    $enabled = setting('ads.enabled', false);
    $testMode = setting('ads.test_mode', true);
    $clientId = setting('ads.client_id', '');
    $labelText = setting('ads.label_text', 'Advertisement');
    
    $slots = setting('ads.slots', []);
    if (!is_array($slots)) {
        $slots = json_decode($slots, true) ?? [];
    }
    
    $slotId = $slots[$slotName] ?? '';
    
    // Check cookie consent (only required for real ads, not test mode)
    $cookieConsent = request()->cookie('cookie_consent');
    $hasConsent = $cookieConsent === 'accepted';
    
    // In test mode: show placeholder if enabled (no consent or slot ID needed)
    // In production mode: require enabled, client ID, slot ID, and consent
    $shouldShow = false;
    if ($testMode && $enabled) {
        $shouldShow = true; // Test mode: just show placeholder
    } elseif (!$testMode && $enabled && $clientId && $slotId && $hasConsent) {
        $shouldShow = true; // Production: need all requirements
    }
@endphp

@if($shouldShow)
    <div class="ad-container my-4">
        <div class="text-xs text-gray-500 mb-1 text-center">{{ $labelText }}</div>
        
        @if($testMode)
            <div class="border-2 border-dashed border-orange-400 bg-orange-50 rounded-lg flex items-center justify-center min-h-[250px] p-4">
                <div class="text-center">
                    <div class="text-orange-600 font-semibold mb-2">AD SLOT: {{ $slotName }}</div>
                    <div class="text-xs text-orange-500">Test Mode Active</div>
                    @if($slotId)
                        <div class="text-xs text-gray-500 mt-1">Slot ID: {{ $slotId }}</div>
                    @else
                        <div class="text-xs text-gray-500 mt-1">No Slot ID configured</div>
                    @endif
                </div>
            </div>
        @else
            <div class="min-h-[250px]" id="ad-{{ $slotName }}">
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="{{ $clientId }}"
                     data-ad-slot="{{ $slotId }}"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
            </div>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        @endif
    </div>
@endif
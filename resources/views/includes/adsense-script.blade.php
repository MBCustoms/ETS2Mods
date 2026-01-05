@php
    $enabled = setting('ads.enabled', false);
    $testMode = setting('ads.test_mode', true);
    $clientId = setting('ads.client_id', '');
    $cookieConsent = request()->cookie('cookie_consent');
    $hasConsent = $cookieConsent === 'accepted';
@endphp

@if($enabled && $clientId && $hasConsent)
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $clientId }}"
            @if($testMode) data-adtest="on" @endif
            crossorigin="anonymous"></script>
@endif

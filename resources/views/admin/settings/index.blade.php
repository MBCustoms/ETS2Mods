@extends('layouts.admin')

@section('header', 'Settings')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="space-y-6">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- General Settings -->
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">General Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Basic site configuration.</p>
            </div>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Site Name -->
                <div class="sm:col-span-3">
                    <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                    <div class="mt-1">
                         <input type="text" name="settings[site][name]" id="site_name" 
                                value="{{ $settings->get('site')?->where('key', 'name')->first()?->value ?? config('app.name') }}"
                                class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Site Email -->
                <div class="sm:col-span-3">
                    <label for="site_email" class="block text-sm font-medium text-gray-700">Support Email</label>
                    <div class="mt-1">
                         <input type="email" name="settings[site][email]" id="site_email" 
                                value="{{ $settings->get('site')?->where('key', 'email')->first()?->value ?? '' }}"
                                class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="sm:col-span-3">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <div class="mt-1">
                        <input type="file" name="logo" id="logo" accept="image/*" 
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               onchange="previewLogo(this)">
                    </div>
                    @php
                        $logoPath = $settings->get('site')?->where('key', 'logo')->first()?->value;
                    @endphp
                    @if($logoPath)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-2">Current Logo:</p>
                            <img id="logo-preview" src="{{ asset($logoPath) }}" alt="Logo" class="h-20 object-contain border border-gray-200 rounded">
                        </div>
                    @else
                        <div class="mt-2">
                            <img id="logo-preview" src="" alt="Logo preview" class="h-20 object-contain border border-gray-200 rounded hidden">
                        </div>
                    @endif
                </div>

                <!-- Favicon Upload -->
                <div class="sm:col-span-3">
                    <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                    <div class="mt-1">
                        <input type="file" name="favicon" id="favicon" accept="image/*" 
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               onchange="previewFavicon(this)">
                    </div>
                    @php
                        $faviconPath = $settings->get('site')?->where('key', 'favicon')->first()?->value;
                    @endphp
                    @if($faviconPath)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-2">Current Favicon:</p>
                            <img id="favicon-preview" src="{{ asset($faviconPath) }}" alt="Favicon" class="h-16 w-16 object-contain border border-gray-200 rounded">
                        </div>
                    @else
                        <div class="mt-2">
                            <img id="favicon-preview" src="" alt="Favicon preview" class="h-16 w-16 object-contain border border-gray-200 rounded hidden">
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">SEO Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Search engine optimization settings.</p>
            </div>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Meta Description -->
                <div class="sm:col-span-6">
                    <label for="seo_meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                    <div class="mt-1">
                        <textarea id="seo_meta_description" name="settings[seo][meta_description]" rows="3" 
                                  class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $settings->get('seo')?->where('key', 'meta_description')->first()?->value ?? '' }}</textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Recommended: 150-160 characters</p>
                </div>
                
                <!-- Meta Keywords -->
                <div class="sm:col-span-6">
                    <label for="seo_meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                    <div class="mt-1">
                        <input type="text" name="settings[seo][meta_keywords]" id="seo_meta_keywords" 
                               value="{{ $settings->get('seo')?->where('key', 'meta_keywords')->first()?->value ?? '' }}"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Comma separated keywords (e.g., mod, game, download)</p>
                </div>
            </div>

            <!-- reCAPTCHA Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">reCAPTCHA Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Google reCAPTCHA configuration for spam protection.</p>
            </div>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Enable reCAPTCHA -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="hidden" name="settings[recaptcha][enabled]" value="0">
                            <input id="recaptcha_enabled" name="settings[recaptcha][enabled]" value="1" type="checkbox" 
                                   {{ ($settings->get('recaptcha')?->where('key', 'enabled')->first()?->value ?? 0) ? 'checked' : '' }}
                                   class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="recaptcha_enabled" class="font-medium text-gray-700">Enable reCAPTCHA</label>
                            <p class="text-gray-500">Enable Google reCAPTCHA on forms (contact, registration, etc.)</p>
                        </div>
                    </div>
                </div>

                <!-- Site Key -->
                <div class="sm:col-span-3">
                    <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700">Site Key</label>
                    <div class="mt-1">
                        <input type="text" name="settings[recaptcha][site_key]" id="recaptcha_site_key" 
                               value="{{ $settings->get('recaptcha')?->where('key', 'site_key')->first()?->value ?? '' }}"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Get your keys from <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-orange-600 hover:text-orange-500">Google reCAPTCHA</a></p>
                </div>

                <!-- Secret Key -->
                <div class="sm:col-span-3">
                    <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700">Secret Key</label>
                    <div class="mt-1">
                        <input type="password" name="settings[recaptcha][secret_key]" id="recaptcha_secret_key" 
                               value="{{ $settings->get('recaptcha')?->where('key', 'secret_key')->first()?->value ?? '' }}"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Keep this secret key secure</p>
                </div>

                <!-- reCAPTCHA Version -->
                <div class="sm:col-span-6">
                    <label for="recaptcha_version" class="block text-sm font-medium text-gray-700">reCAPTCHA Version</label>
                    <div class="mt-1">
                        <select id="recaptcha_version" name="settings[recaptcha][version]" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="v2" {{ ($settings->get('recaptcha')?->where('key', 'version')->first()?->value ?? 'v2') == 'v2' ? 'selected' : '' }}>reCAPTCHA v2 (Checkbox)</option>
                            <option value="v3" {{ ($settings->get('recaptcha')?->where('key', 'version')->first()?->value ?? '') == 'v3' ? 'selected' : '' }}>reCAPTCHA v3 (Invisible)</option>
                        </select>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Ensure your keys match the selected version.</p>
                </div>
            </div>

            <!-- Redirect Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Redirect Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Configuration for the download redirect page.</p>
            </div>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Redirect Text -->
                <div class="sm:col-span-6">
                    <label for="redirect_text" class="block text-sm font-medium text-gray-700">Redirect Text</label>
                    <div class="mt-1">
                        <input type="text" name="settings[redirect][text]" id="redirect_text" 
                               value="{{ $settings->get('redirect')?->where('key', 'text')->first()?->value ?? 'Your download will contain:' }}"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Redirect Timer -->
                <div class="sm:col-span-3">
                    <label for="redirect_timer" class="block text-sm font-medium text-gray-700">Timer (Seconds)</label>
                    <div class="mt-1">
                        <input type="number" name="settings[redirect][timer]" id="redirect_timer" 
                               value="{{ $settings->get('redirect')?->where('key', 'timer')->first()?->value ?? 5 }}"
                               min="0" max="60"
                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Time to wait before redirecting.</p>
                </div>
            </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end border-t border-gray-200 pt-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Save All Settings
            </button>
        </div>
    </form>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFavicon(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('favicon-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

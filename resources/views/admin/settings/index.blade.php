@extends('layouts.admin')

@section('header', 'Settings')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg" x-data="{ activeTab: 'ads' }">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex" aria-label="Tabs">
            <button @click="activeTab = 'general'" 
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'general', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'general' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                General
            </button>
            <button @click="activeTab = 'ads'" 
                :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'ads', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ads' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                Ads & Monetization
            </button>
        </nav>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
        @csrf
        
        <!-- General Tab -->
        <div x-show="activeTab === 'general'" class="space-y-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">General Information</h3>
                <p class="mt-1 text-sm text-gray-500">Basic details about your mod platform.</p>
            </div>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Site Name -->
                <div class="sm:col-span-3">
                    <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                    <div class="mt-1">
                         <input type="text" name="settings[site][name]" id="site_name" 
                                value="{{ $settings->get('site')?->where('key', 'name')->first()?->value ?? config('app.name') }}"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Site Email -->
                <div class="sm:col-span-3">
                    <label for="site_email" class="block text-sm font-medium text-gray-700">Support Email</label>
                    <div class="mt-1">
                         <input type="email" name="settings[site][email]" id="site_email" 
                                value="{{ $settings->get('site')?->where('key', 'email')->first()?->value ?? '' }}"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Meta Description -->
                <div class="sm:col-span-6">
                    <label for="seo_description" class="block text-sm font-medium text-gray-700">SEO Meta Description</label>
                    <div class="mt-1">
                        <textarea id="seo_description" name="settings[seo][meta_description]" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ $settings->get('seo')?->where('key', 'meta_description')->first()?->value ?? '' }}</textarea>
                    </div>
                </div>
                
                <!-- Meta Keywords -->
                <div class="sm:col-span-6">
                    <label for="seo_keywords" class="block text-sm font-medium text-gray-700">SEO Keywords</label>
                    <div class="mt-1">
                        <input type="text" name="settings[seo][meta_keywords]" id="seo_keywords" 
                               value="{{ $settings->get('seo')?->where('key', 'meta_keywords')->first()?->value ?? '' }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Comma separated keywords.</p>
                </div>
            </div>
        </div>

        <!-- Ads Tab -->
        <div x-show="activeTab === 'ads'" class="space-y-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ad Management</h3>
                <p class="mt-1 text-sm text-gray-500">Configure Google AdSense or custom ad slots. Changes take effect immediately but check cache.</p>
            </div>

            <!-- Global Toggle -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="hidden" name="settings[ads][enabled]" value="0">
                    <input id="ads_enabled" name="settings[ads][enabled]" value="1" type="checkbox" 
                           {{ ($settings->get('ads')?->where('key', 'enabled')->first()?->value ?? 0) ? 'checked' : '' }}
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="ads_enabled" class="font-medium text-gray-700">Enable Ads Globally</label>
                    <p class="text-gray-500">Unchecking this disables all ad slots instantly.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Provider -->
                <div class="sm:col-span-3">
                    <label for="ads_provider" class="block text-sm font-medium text-gray-700">Ad Provider</label>
                    <div class="mt-1">
                        <select id="ads_provider" name="settings[ads][provider]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="adsense" {{ ($settings->get('ads')?->where('key', 'provider')->first()?->value ?? 'adsense') == 'adsense' ? 'selected' : '' }}>Google AdSense</option>
                            <option value="custom" {{ ($settings->get('ads')?->where('key', 'provider')->first()?->value ?? '') == 'custom' ? 'selected' : '' }}>Custom HTML</option>
                        </select>
                    </div>
                </div>

                <!-- Client ID -->
                 <div class="sm:col-span-3">
                    <label for="ads_client_id" class="block text-sm font-medium text-gray-700">AdSense Client ID (pub-xxxxxxxx)</label>
                    <div class="mt-1">
                        <input type="text" name="settings[ads][client_id]" id="ads_client_id" 
                               value="{{ $settings->get('ads')?->where('key', 'client_id')->first()?->value ?? '' }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                
                <!-- Visibility Rules -->
                <div class="sm:col-span-6 space-y-2">
                     <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Visibility Rules</legend>
                        <div class="mt-2 space-y-2">
                             <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="hidden" name="settings[ads][show_for_guests]" value="0">
                                    <input id="show_for_guests" name="settings[ads][show_for_guests]" type="checkbox" value="1" 
                                           {{ ($settings->get('ads')?->where('key', 'show_for_guests')->first()?->value ?? 1) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_for_guests" class="font-medium text-gray-700">Show to Guests</label>
                                </div>
                            </div>
                            
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="hidden" name="settings[ads][show_for_users]" value="0">
                                    <input id="show_for_users" name="settings[ads][show_for_users]" type="checkbox" value="1" 
                                           {{ ($settings->get('ads')?->where('key', 'show_for_users')->first()?->value ?? 1) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_for_users" class="font-medium text-gray-700">Show to Registered Users</label>
                                </div>
                            </div>

                             <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="hidden" name="settings[ads][hide_for_verified]" value="0">
                                    <input id="hide_for_verified" name="settings[ads][hide_for_verified]" type="checkbox" value="1" 
                                           {{ ($settings->get('ads')?->where('key', 'hide_for_verified')->first()?->value ?? 0) ? 'checked' : '' }}
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="hide_for_verified" class="font-medium text-gray-700">Hide for Verified Users (Perk)</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <!-- Global Head Script -->
                <div class="sm:col-span-6">
                    <label for="ads_head_script" class="block text-sm font-medium text-gray-700">Global Head Script</label>
                    <div class="mt-1">
                        <textarea id="ads_head_script" name="settings[ads][head_script]" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono text-xs">{{ $settings->get('ads')?->where('key', 'head_script')->first()?->value ?? '' }}</textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Example: AdSense Auto-ads script. Injected into &lt;head&gt;.</p>
                </div>

                <!-- Ad Slots -->
                <div class="sm:col-span-6">
                    <h4 class="text-md font-medium text-gray-900 mt-4 mb-2">Ad Slots</h4>
                    <div class="bg-gray-50 p-4 rounded-md space-y-4">
                        
                        <!-- Sidebar Slot -->
                        <div>
                            <label for="slot_sidebar" class="block text-sm font-medium text-gray-700">Sidebar (Top)</label>
                            <div class="mt-1">
                                <textarea id="slot_sidebar" name="settings[ads][slot_sidebar]" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono text-xs">{{ $settings->get('ads')?->where('key', 'slot_sidebar')->first()?->value ?? '' }}</textarea>
                            </div>
                        </div>

                         <!-- Mod Detail Bottom -->
                        <div>
                            <label for="slot_mod_detail_bottom" class="block text-sm font-medium text-gray-700">Mod Detail (Bottom)</label>
                            <div class="mt-1">
                                <textarea id="slot_mod_detail_bottom" name="settings[ads][slot_mod_detail_bottom]" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md font-mono text-xs">{{ $settings->get('ads')?->where('key', 'slot_mod_detail_bottom')->first()?->value ?? '' }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection

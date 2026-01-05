<div class="space-y-6">
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6 text-gray-800">Google AdSense Settings</h2>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="enabled" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-700">Enable Ads</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Toggle to show/hide all ads on the site</p>
                </div>

                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="test_mode" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm font-medium text-gray-700">Test Mode</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Show placeholders or test ads (safe for clicking)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    AdSense Client ID
                </label>
                <input 
                    type="text" 
                    wire:model="client_id" 
                    placeholder="ca-pub-XXXXXXXXXXXXXXXX"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                @error('client_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Your Google AdSense publisher ID</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Ad Label Text
                </label>
                <input 
                    type="text" 
                    wire:model="label_text" 
                    placeholder="Advertisement"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                >
                @error('label_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Text displayed above each ad unit (required by Google)</p>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Ad Slot IDs</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Home Top
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.home_top" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Home Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.home_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mod Detail Top
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.mod_detail_top" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mod Detail Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.mod_detail_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mod Detail Inline
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.mod_detail_inline" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.category_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Search Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.search_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Profile Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.profile_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Page Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.page_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Create Mod Sidebar
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.create_sidebar" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Home Inline (between mods)
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.home_inline" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category Inline (between mods)
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.category_inline" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Search Inline (between mods)
                        </label>
                        <input 
                            type="text" 
                            wire:model="slots.search_inline" 
                            placeholder="Slot ID"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                >
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <h3 class="font-semibold text-orange-900 mb-2">⚠️ Important Notes</h3>
        <ul class="text-sm text-orange-800 space-y-1 list-disc list-inside">
            <li>Always enable Test Mode before clicking on ads during development</li>
            <li>Test Mode uses data-adtest="on" attribute (safe for Google policies)</li>
            <li>Disable ads completely if you haven't been approved by Google yet</li>
            <li>Ad labels are required by Google AdSense policies</li>
        </ul>
    </div>
</div>

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Platform Settings</h2>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="border-t border-gray-200 p-4">
             <form wire:submit.prevent="save">
                @foreach($groupedSettings as $group => $items)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 capitalize mb-4 border-b pb-2">{{ $group }} Settings</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            @foreach($items as $setting)
                                <div class="sm:col-span-3">
                                    <label for="{{ $group }}.{{ $setting->key }}" class="block text-sm font-medium text-gray-700 capitalize">
                                        {{ str_replace('_', ' ', $setting->key) }}
                                    </label>
                                    <div class="mt-1">
                                        @if($setting->type === 'boolean')
                                            <select wire:model="settings.{{ $group }}.{{ $setting->key }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                        @else
                                            <input type="text" wire:model="settings.{{ $group }}.{{ $setting->key }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                <div class="flex justify-end pt-5">
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Mod Versions</h3>
                <p class="mt-1 text-sm text-gray-600">Manage release history. The latest active version is always shown to users.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            
            <!-- Add New Version Form -->
            <div class="shadow overflow-hidden sm:rounded-md bg-white mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="version_number" class="block text-sm font-medium text-gray-700">Version Number</label>
                            <input type="text" wire:model="version_number" id="version_number" placeholder="e.g. 1.2.0" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            @error('version_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="game_version" class="block text-sm font-medium text-gray-700">Game Version</label>
                            <input type="text" wire:model="game_version" id="game_version" placeholder="e.g. 1.50" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="file_size" class="block text-sm font-medium text-gray-700">File Size</label>
                            <input type="text" wire:model="file_size" id="file_size" placeholder="e.g. 150 MB" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                        </div>

                        <div class="col-span-6">
                            <label for="download_url" class="block text-sm font-medium text-gray-700">Download URL</label>
                            <input type="text" wire:model="download_url" id="download_url" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            @error('download_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="col-span-6">
                            <label for="changelog" class="block text-sm font-medium text-gray-700">Changelog</label>
                             <textarea wire:model="changelog" id="changelog" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button wire:click="store" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Add Version
                    </button>
                    @if (session()->has('success'))
                        <span class="text-green-600 text-sm ml-2">{{ session('success') }}</span>
                    @endif
                </div>
            </div>

            <!-- Version List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    @forelse($versions as $version)
                    <li>
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div class="truncate">
                                    <div class="flex text-sm">
                                        <p class="font-medium text-orange-600 truncate">v{{ $version->version_number }}</p>
                                        <p class="ml-1 flex-shrink-0 font-normal text-gray-500">for ETS2 {{ $version->game_version }}</p>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <p>{{ $version->downloads_count }} downloads</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                                    <button wire:click="toggleStatus({{ $version->id }})" class="mr-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded {{ $version->is_active ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">
                                        {{ $version->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                     <button wire:click="delete({{ $version->id }})" wire:confirm="Are you sure you want to delete this version?" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-gray-500">No versions found.</li>
                    @endforelse
                </ul>
            </div>
            
        </div>
    </div>
</div>

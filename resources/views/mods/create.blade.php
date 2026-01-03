@extends('layouts.app')

@section('title', 'Submit a Mod')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Submit a New Mod</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Share your creation with the community. Please review our guidelines before submitting.</p>
                </div>
                
                @if ($errors->any())
                    <div class="mt-4 bg-red-50 p-4 rounded-md">
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

                <form class="mt-5 space-y-6" action="{{ route('mods.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-900">Mod Title</label>
                            <div class="mt-1">
                                <input type="text" name="title" id="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-900">Category</label>
                            <div class="mt-1">
                                <select id="category_id" name="category_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">Version Details</h4>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                                <div>
                                    <label for="version_number" class="block text-sm font-medium text-gray-700">Mod Version</label>
                                    <div class="mt-1">
                                        <input type="text" name="version_number" id="version_number" value="{{ old('version_number', '1.0') }}" placeholder="1.0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm">
                                    </div>
                                    @error('version_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                <div>
                                    <label for="game_version" class="block text-sm font-medium text-gray-700">Game Version</label>
                                    <div class="mt-1">
                                        <input type="text" name="game_version" id="game_version" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" placeholder="1.50" value="{{ old('game_version') }}">
                                    </div>
                                </div>
    
                                <div>
                                    <label for="file_size" class="block text-sm font-medium text-gray-700">File Size</label>
                                    <div class="mt-1">
                                        <input type="text" name="file_size" id="file_size" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" placeholder="150 MB" value="{{ old('file_size') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="download_url" class="block text-sm font-medium text-gray-900">Download URL</label>
                            <div class="mt-1">
                                <input type="url" name="download_url" id="download_url" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" placeholder="https://sharemods.com/..." value="{{ old('download_url') }}" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Link to external file host.</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="8" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" required>{{ old('description') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Markdown supported.</p>
                        </div>

                        <div>
                            <label for="credits" class="block text-sm font-medium text-gray-900">Credits (optional)</label>
                            <div class="mt-1">
                                <input type="text" name="credits" id="credits" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:text-sm" value="{{ old('credits') }}" placeholder="e.g. Model by John Doe, textures from ...">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Give credit to contributors or assets used.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Mod Images</label>
                            <p class="text-sm text-gray-500 mb-4">Upload multiple images for your mod. First image will be used as the main thumbnail.</p>
                            
                            <div class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50 hover:border-indigo-400 transition-colors" id="upload-area">
                                <div class="text-center" id="upload-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center items-center">
                                        <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500 px-4 py-2 border border-indigo-600 rounded-md">
                                            <span>Choose files</span>
                                            <input id="file-upload" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                        </label>
                                        <p class="ml-3 text-gray-500">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to 5MB each</p>
                                    <p class="text-xs leading-5 text-gray-400 mt-1" id="file-count">No files selected</p>
                                </div>
                            </div>
                            
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 hidden">
                                <!-- Preview images will be inserted here -->
                            </div>
                            
                            <div id="upload-progress" class="mt-4 hidden">
                                <div class="bg-gray-200 rounded-full h-2.5">
                                    <div id="progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-center" id="progress-text">0%</p>
                            </div>
                        </div>

                        <script>
                            let selectedFiles = [];
                            
                            function previewImages(input) {
                                const preview = document.getElementById('image-preview');
                                const placeholder = document.getElementById('upload-placeholder');
                                const fileCount = document.getElementById('file-count');
                                const uploadArea = document.getElementById('upload-area');
                                
                                if (input.files && input.files.length > 0) {
                                    selectedFiles = Array.from(input.files);
                                    preview.innerHTML = '';
                                    preview.classList.remove('hidden');
                                    placeholder.classList.add('hidden');
                                    uploadArea.classList.add('hidden');
                                    
                                    const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
                                    const totalSizeMB = (totalSize / (1024 * 1024)).toFixed(2);
                                    fileCount.textContent = `${selectedFiles.length} file(s) selected (${totalSizeMB} MB)`;
                                    
                                    selectedFiles.forEach((file, index) => {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const div = document.createElement('div');
                                            div.className = 'relative group';
                                            div.dataset.index = index;
                                            
                                            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                                            const isMain = index === 0;
                                            
                                            div.innerHTML = `
                                                <div class="relative">
                                                    ${isMain ? '<span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded z-10">Main</span>' : ''}
                                                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg border-2 ${isMain ? 'border-indigo-500' : 'border-gray-300'}">
                                                    <button type="button" onclick="removeImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-600">
                                                    <p class="truncate" title="${file.name}">${file.name}</p>
                                                    <p class="text-gray-500">${fileSize} MB</p>
                                                </div>
                                            `;
                                            preview.appendChild(div);
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                } else {
                                    preview.classList.add('hidden');
                                    placeholder.classList.remove('hidden');
                                    uploadArea.classList.remove('hidden');
                                    fileCount.textContent = 'No files selected';
                                }
                            }
                            
                            function removeImage(index) {
                                const input = document.getElementById('file-upload');
                                const dt = new DataTransfer();
                                
                                selectedFiles.splice(index, 1);
                                selectedFiles.forEach(file => dt.items.add(file));
                                input.files = dt.files;
                                
                                previewImages(input);
                            }
                            
                            // Drag and drop
                            const uploadArea = document.getElementById('upload-area');
                            uploadArea.addEventListener('dragover', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                uploadArea.classList.remove('border-gray-300');
                                uploadArea.classList.add('border-indigo-500', 'bg-indigo-50');
                            });
                            
                            uploadArea.addEventListener('dragleave', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');
                                uploadArea.classList.add('border-gray-300');
                            });
                            
                            uploadArea.addEventListener('drop', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');
                                uploadArea.classList.add('border-gray-300');
                                
                                const input = document.getElementById('file-upload');
                                const files = e.dataTransfer.files;
                                
                                // Merge with existing files
                                const dt = new DataTransfer();
                                Array.from(input.files).forEach(file => dt.items.add(file));
                                Array.from(files).forEach(file => {
                                    if (file.type.startsWith('image/')) {
                                        dt.items.add(file);
                                    }
                                });
                                
                                input.files = dt.files;
                                previewImages(input);
                            });
                            
                            // File size validation
                            document.getElementById('file-upload').addEventListener('change', function(e) {
                                const newFiles = Array.from(e.target.files || []);
                                const maxSize = 5 * 1024 * 1024; // 5MB

                                // Merge with existing selectedFiles (avoid duplicates by name+size)
                                newFiles.forEach(f => {
                                    const exists = selectedFiles.some(sf => sf.name === f.name && sf.size === f.size);
                                    if (!exists) selectedFiles.push(f);
                                });

                                // Validate sizes and filter invalid
                                const invalidFiles = selectedFiles.filter(file => file.size > maxSize);
                                if (invalidFiles.length > 0) {
                                    alert(`Some files exceed 5MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                                    selectedFiles = selectedFiles.filter(file => file.size <= maxSize);
                                }

                                // Rebuild input.files from selectedFiles
                                const dt = new DataTransfer();
                                selectedFiles.forEach(file => dt.items.add(file));
                                e.target.files = dt.files;

                                previewImages(e.target);
                            });
                        </script>
                    </div>

                    <div class="pt-5 flex justify-end gap-x-3">
                        <button type="button" class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit Mod</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

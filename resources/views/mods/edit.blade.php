@extends('layouts.app')

@section('title', 'Edit Mod: ' . $mod->title)

@push('styles')
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="bg-gray-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <div class="lg:w-3/4">
                <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Mod</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Update your mod details. Changes may require re-approval.</p>
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

                <form class="mt-5 space-y-6" action="{{ route('mods.update', $mod) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-900">Mod Title</label>
                            <div class="mt-1">
                                <input type="text" name="title" id="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" value="{{ old('title', $mod->title) }}" required>
                            </div>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-900">Category</label>
                            <div class="mt-1">
                                <select id="category_id" name="category_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $mod->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                        <!-- Assuming latestVersion holds current version info even if we simplify management -->
                                        <input type="text" name="version_number" id="version_number" value="{{ old('version_number', $mod->latestVersion?->version_number ?? '1.0') }}" placeholder="1.0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm">
                                    </div>
                                    @error('version_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
    
                                <div>
                                    <label for="game_version" class="block text-sm font-medium text-gray-700">Game Version</label>
                                    <div class="mt-1">
                                        <input type="text" name="game_version" id="game_version" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="1.50" value="{{ old('game_version', $mod->game_version ?? $mod->latestVersion?->game_version) }}">
                                    </div>
                                </div>
    
                                <div>
                                    <label for="file_size" class="block text-sm font-medium text-gray-700">File Size</label>
                                    <div class="mt-1">
                                        <input type="text" name="file_size" id="file_size" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="150 MB" value="{{ old('file_size', $mod->file_size ?? $mod->latestVersion?->file_size) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="download_url" class="block text-sm font-medium text-gray-900">Download URL</label>
                            <div class="mt-1">
                                <input type="url" name="download_url" id="download_url" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="https://sharemods.com/..." value="{{ old('download_url', $mod->download_url) }}" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Link to external file host (ShareMods, etc.).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Alternative Download Links (Optional)</label>
                            <div id="download-links-container" class="space-y-3">
                                @php
                                    $links = old('download_links', $mod->download_links ?? []);
                                @endphp
                                @foreach($links as $index => $link)
                                    <div class="flex gap-2 download-link-item">
                                        <input type="text" name="download_links[{{ $index }}][label]" value="{{ $link['label'] ?? '' }}" 
                                               class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                               placeholder="Label (e.g. Steam)">
                                        <input type="url" name="download_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" 
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                               placeholder="https://..." required>
                                        <button type="button" onclick="this.closest('.download-link-item').remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addDownloadLink()" class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-medium">+ Add Link</button>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="8" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" required>{{ old('description', $mod->description) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Markdown supported.</p>
                        </div>

                        <div>
                            <label for="credits" class="block text-sm font-medium text-gray-900">Credits (optional)</label>
                            <div class="mt-1">
                                <input type="text" name="credits" id="credits" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" value="{{ old('credits', $mod->credits) }}" placeholder="e.g. Model by John Doe, textures from ...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">YouTube Videos (optional)</label>
                            <p class="text-sm text-gray-500 mb-4">Add YouTube video links to showcase your mod.</p>
                            <div id="youtube-videos-container" class="space-y-3">
                                @php
                                    $videos = old('youtube_videos', $mod->youtube_videos ?? []);
                                    if (empty($videos)) {
                                        $videos = [''];
                                    }
                                @endphp
                                @foreach($videos as $index => $video)
                                    <div class="flex gap-2 youtube-video-item">
                                        <input type="url" name="youtube_videos[]" value="{{ $video }}" 
                                               class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                               placeholder="https://www.youtube.com/watch?v=...">
                                        @if($index > 0 || count($videos) > 1)
                                            <button type="button" onclick="removeYoutubeVideo(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addYoutubeVideo()" class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-medium">+ Add Another Video</button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Mod Images</label>
                            <p class="text-sm text-gray-500 mb-4">Manage existing images or upload new ones.</p>
                            
                            <!-- Existing Images -->
                             @if($mod->modImages->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-6">
                                    @foreach($mod->modImages as $image)
                                        <div class="relative group" id="existing-image-{{ $image->id }}">
                                            <img src="{{ $image->url }}" alt="Mod Image" class="w-full h-32 object-cover rounded-lg border-2 {{ $image->is_main ? 'border-orange-500' : 'border-gray-300' }}">
                                            @if($image->is_main)
                                                <span class="absolute top-2 left-2 bg-orange-600 text-white text-xs px-2 py-1 rounded z-10">Main</span>
                                            @endif
                                            <div class="absolute inset-0 bg-opacity-0 group-hover:bg-opacity-40 transition-opacity rounded-lg flex items-center justify-center">
                                                <button type="button" onclick="markForDeletion({{ $image->id }})" class="bg-red-600 text-white px-3 py-1 rounded-md text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="deletion-inputs"></div>
                            @endif

                            <div class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50 hover:border-orange-400 transition-colors" id="upload-area">
                                <div class="text-center" id="upload-placeholder">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center items-center">
                                        <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-orange-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-orange-600 focus-within:ring-offset-2 hover:text-orange-500 px-4 py-2 border border-orange-600 rounded-md">
                                            <span>Add more files</span>
                                            <input id="file-upload" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(this)">
                                        </label>
                                        <p class="ml-3 text-gray-500">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to 5MB each</p>
                                    <p class="text-xs leading-5 text-gray-400 mt-1" id="file-count">No new files selected</p>
                                </div>
                            </div>
                            
                            <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 hidden"></div>
                        </div>

                        <script>
                            // reuse similar script from create.blade.php but adapted
                             let selectedFiles = [];
                            
                             function markForDeletion(imageId) {
                                if (confirm('Delete this image? It will be removed when you save.')) {
                                    const container = document.getElementById('deletion-inputs');
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'remove_images[]';
                                    input.value = imageId;
                                    container.appendChild(input);
                                    
                                    const imgDiv = document.getElementById(`existing-image-${imageId}`);
                                    imgDiv.style.opacity = '0.5';
                                    imgDiv.querySelector('button').innerText = 'Marked for deletion';
                                    imgDiv.querySelector('button').disabled = true;
                                }
                             }

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
                                            div.innerHTML = `
                                                <div class="relative">
                                                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg border-2 border-green-500">
                                                    <button type="button" onclick="removeNewImage(${index})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="mt-2 text-xs text-gray-600">
                                                    <p class="truncate">${file.name}</p>
                                                    <p class="text-green-600 font-medium">New</p>
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
                                    fileCount.textContent = 'No new files selected';
                                }
                            }
                            
                            function removeNewImage(index) {
                                const input = document.getElementById('file-upload');
                                const dt = new DataTransfer();
                                
                                selectedFiles.splice(index, 1);
                                selectedFiles.forEach(file => dt.items.add(file));
                                input.files = dt.files;
                                
                                previewImages(input);
                            }

                            function addYoutubeVideo() {
                                const container = document.getElementById('youtube-videos-container');
                                const div = document.createElement('div');
                                div.className = 'flex gap-2 youtube-video-item';
                                div.innerHTML = `
                                    <input type="url" name="youtube_videos[]" value="" 
                                           class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    <button type="button" onclick="removeYoutubeVideo(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                                `;
                                container.appendChild(div);
                            }

                            function removeYoutubeVideo(button) {
                                button.closest('.youtube-video-item').remove();
                            }

                            function addDownloadLink() {
                                const container = document.getElementById('download-links-container');
                                const index = container.children.length;
                                const div = document.createElement('div');
                                div.className = 'flex gap-2 download-link-item';
                                div.innerHTML = `
                                    <input type="text" name="download_links[${index}][label]" 
                                           class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                           placeholder="Label (e.g. Steam)">
                                    <input type="url" name="download_links[${index}][url]" 
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                           placeholder="https://..." required>
                                    <button type="button" onclick="this.closest('.download-link-item').remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                                `;
                                container.appendChild(div);
                            }
                        </script>
                    </div>

                    <div class="pt-5 flex justify-end gap-x-3">
                        <button type="button" class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="rounded-md bg-orange-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Update Mod</button>
                    </div>
                </form>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="create_sidebar" />
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '#description',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            min_height: 500,
            image_caption: true,
            branding: false
        });
    });
</script>
@endpush
@endsection

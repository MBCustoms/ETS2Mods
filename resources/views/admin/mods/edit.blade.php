@extends('layouts.admin')

@section('header', 'Edit Mod: ' . $mod->title)

@push('styles')
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="border-t border-gray-200 p-6">
        <form action="{{ route('admin.mods.update', $mod) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
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

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Title -->
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" value="{{ old('title', $mod->title) }}" required class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>

                <!-- Category -->
                <div class="sm:col-span-2">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <select id="category_id" name="category_id" required class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ $mod->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Slug -->
                <div class="sm:col-span-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700">URL Slug</label>
                    <div class="mt-1">
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $mod->slug) }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2 bg-gray-50" readonly>
                        <p class="mt-1 text-xs text-gray-500">Slug is auto-generated from title. Cannot be edited.</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="10" required class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">{{ old('description', $mod->description) }}</textarea>
                    </div>
                </div>

                <!-- Download URL via Latest Version -->
                <div class="sm:col-span-6">
                    <label for="download_url" class="block text-sm font-medium text-gray-700">Main Download URL <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="url" name="download_url" id="download_url" value="{{ old('download_url', $mod->download_url) }}" required class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                         <p class="mt-1 text-xs text-gray-500">Direct link to the file on external host. This updates the latest version URL.</p>
                    </div>
                </div>

                <!-- Alternative Download Links -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alternative Download Links</label>
                    <div id="download-links-container" class="space-y-3">
                        @php
                            $links = old('download_links', $mod->download_links ?? []);
                        @endphp
                        @foreach($links as $index => $link)
                            <div class="flex gap-2 download-link-item">
                                <input type="text" name="download_links[{{ $index }}][label]" value="{{ $link['label'] ?? '' }}" 
                                       class="w-1/3 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                                       placeholder="Label (e.g. Steam)">
                                <input type="url" name="download_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" 
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                                       placeholder="https://..." required>
                                <button type="button" onclick="this.closest('.download-link-item').remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addDownloadLink()" class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-medium">+ Add Alternative Link</button>
                </div>

                <!-- Credits -->
                <div class="sm:col-span-6">
                    <label for="credits" class="block text-sm font-medium text-gray-700">Credits</label>
                    <div class="mt-1">
                        <input type="text" name="credits" id="credits" value="{{ old('credits', $mod->credits) }}" placeholder="e.g. Model by John Doe, textures from..." class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>

               <!-- YouTube Videos -->
               <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Videos</label>
                    <div id="youtube-videos-container" class="space-y-3">
                        @php
                            $videos = old('youtube_videos', $mod->youtube_videos ?? []);
                            if (empty($videos)) $videos = ['']; // At least one empty input if none
                        @endphp
                        @foreach($videos as $index => $video)
                            <div class="flex gap-2 youtube-video-item">
                                <input type="url" name="youtube_videos[]" value="{{ $video }}" 
                                       class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <button type="button" onclick="removeYoutubeVideo(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addYoutubeVideo()" class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-medium">+ Add Another Video</button>
               </div>

                <!-- Images Management -->
                <div class="sm:col-span-6 border-t border-gray-200 pt-6 mt-2">
                    <label class="block text-lg font-medium text-gray-900 mb-4">Mod Images</label>
                    
                    <!-- Existing Images -->
                    @if($mod->modImages && $mod->modImages->count() > 0)
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

                    <!-- Upload New -->
                    <div class="flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50 hover:border-orange-400 transition-colors cursor-pointer" onclick="document.getElementById('file-upload').click()">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                <span class="font-semibold text-orange-600 hover:text-orange-500">Upload new images</span>
                                <input id="file-upload" name="images[]" type="file" class="hidden" multiple accept="image/*" onchange="previewImages(this)">
                            </div>
                             <p class="text-xs text-gray-500 mt-2" id="file-count">No new files selected</p>
                        </div>
                    </div>
                     <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 hidden"></div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-6 border-t border-gray-200 pt-6 mt-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <select id="status" name="status" required class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2" onchange="toggleRejectionReason(this.value)">
                            <option value="pending" {{ $mod->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $mod->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $mod->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Rejection Reason -->
                <div class="sm:col-span-6" id="rejection_container" style="{{ $mod->status == 'rejected' ? '' : 'display:none;' }}">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                    <div class="mt-1">
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" placeholder="Explain why this mod was rejected..." class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md border p-2">{{ old('rejection_reason', $mod->rejection_reason) }}</textarea>
                    </div>
                </div>
                
                 <!-- Featured -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ $mod->is_featured ? 'checked' : '' }} class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-gray-700">Featured Mod</label>
                            <p class="text-gray-500">Enable to show this mod on the homepage featured section.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.mods.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Update Mod
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRejectionReason(status) {
        const container = document.getElementById('rejection_container');
        container.style.display = (status === 'rejected') ? 'block' : 'none';
    }

    // Download Links Logic
    function addDownloadLink() {
        const container = document.getElementById('download-links-container');
        const index = container.children.length;
        const div = document.createElement('div');
        div.className = 'flex gap-2 download-link-item';
        div.innerHTML = `
            <input type="text" name="download_links[${index}][label]" 
                   class="w-1/3 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                   placeholder="Label (e.g. Steam)">
            <input type="url" name="download_links[${index}][url]" 
                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                   placeholder="https://..." required>
            <button type="button" onclick="this.closest('.download-link-item').remove()" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
        `;
        container.appendChild(div);
    }

    // Youtube Logic
    function addYoutubeVideo() {
        const container = document.getElementById('youtube-videos-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 youtube-video-item';
        div.innerHTML = `
            <input type="url" name="youtube_videos[]" value="" 
                   class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm p-2 bg-gray-50" 
                   placeholder="https://www.youtube.com/watch?v=...">
            <button type="button" onclick="removeYoutubeVideo(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
        `;
        container.appendChild(div);
    }
    function removeYoutubeVideo(button) {
        if(document.querySelectorAll('.youtube-video-item').length > 1) {
            button.closest('.youtube-video-item').remove();
        } else {
             // ensure at least one remains, or allow clearing value
             button.previousElementSibling.value = '';
        }
    }

    // Image Logic
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
            imgDiv.querySelector('button').innerText = 'Marked';
            imgDiv.querySelector('button').disabled = true;
        }
    }

    function previewImages(input) {
        const preview = document.getElementById('image-preview');
        const fileCount = document.getElementById('file-count');
        
        if (input.files && input.files.length > 0) {
            preview.innerHTML = '';
            preview.classList.remove('hidden');
            fileCount.textContent = `${input.files.length} new files selected`;
            
            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded-lg border-2 border-green-500">
                        <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
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

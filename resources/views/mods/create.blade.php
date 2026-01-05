@extends('layouts.app')

@section('title', 'Submit a Mod')

@push('styles')
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
@endpush

@section('content')
<div class="bg-gray-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <div class="lg:w-3/4">
                <div class="bg-white shadow sm:rounded-lg mt-8 border-t-4 border-orange-500">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">
                            Submit a New Mod
                        </h3>
                        <div class="mt-2 text-sm text-gray-600 space-y-3">
                            <p>
                                Here you can submit and share your own mods with the community.
                                Use the form below to upload your mod and provide all required details.
                                <strong>Please read the guidelines carefully before submitting.</strong>
                            </p>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li>
                                    We reserve the right to edit the <strong>title, description, download links</strong>,
                                    or any other inaccurate or incomplete information.
                                </li>
                                <li>
                                    <strong>Title and description must be written in English.</strong>
                                </li>
                                <li>
                                    Only <strong>in-game screenshots</strong> are allowed.
                                </li>
                                <li>
                                    <strong>No ads, alcohol, drugs, or adult content</strong>.
                                </li>
                                <li>
                                    Please upload <strong>only your own mods</strong>.
                                </li>
                                <li class="text-red-600 font-medium">
                                    Leaked or paid mods are strictly forbidden. Sharing them will result in a
                                    <strong>permanent ban</strong>.
                                </li>
                            </ul>

                            <p class="pt-2">
                                If you need help, please <a href="{{ route('contact.index') }}"
                                class="text-orange-600 hover:underline font-medium">
                                    contact us
                                </a>.
                            </p>

                            <p class="font-semibold text-gray-800 pt-1">
                                Thank you for contributing to {{ setting('site.name', config('app.name', 'ETS2LT')) }}! 🚚
                            </p>
                        </div>
                        <br>
                        <hr>
                        <br>
                
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
                                        <input type="text" name="title" id="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" value="{{ old('title') }}" required>
                                    </div>
                                </div>

                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-900">Category</label>
                                    <div class="mt-1">
                                        <select id="category_id" name="category_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" required>
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
                                                <input type="text" name="version_number" id="version_number" value="{{ old('version_number', '1.0') }}" placeholder="1.0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm">
                                            </div>
                                            @error('version_number')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
    
                                        <div>
                                            <label for="game_version" class="block text-sm font-medium text-gray-700">Game Version</label>
                                            <div class="mt-1">
                                                <input type="text" name="game_version" id="game_version" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="1.50" value="{{ old('game_version') }}">
                                            </div>
                                            @error('game_version')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
    
                                        <div>
                                            <label for="file_size" class="block text-sm font-medium text-gray-700">File Size</label>
                                            <div class="mt-1">
                                                <input type="text" name="file_size" id="file_size" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="150 MB" value="{{ old('file_size') }}">
                                            </div>
                                            @error('file_size')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="download_url" class="block text-sm font-medium text-gray-900">Main Download URL</label>
                                    <div class="mt-1">
                                        <input type="url" name="download_url" id="download_url" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" placeholder="https://sharemods.com/..." value="{{ old('download_url') }}" required>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Link to external file host (ShareMods, etc.).</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">Alternative Download Links (Optional)</label>
                                    <div id="download-links-container" class="space-y-3">
                                        @php
                                            $links = old('download_links', []);
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
                                        <textarea id="description" name="description" rows="10" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" required>{{ old('description') }}</textarea>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Detailed description of your mod. Markdown supported (bold, italic, lists, links, etc.).</p>
                                </div>

                                <div>
                                    <label for="credits" class="block text-sm font-medium text-gray-900">Credits (optional)</label>
                                    <div class="mt-1">
                                        <input type="text" name="credits" id="credits" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" value="{{ old('credits') }}" placeholder="e.g. Model by John Doe, textures from ...">
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Give credit to contributors or assets used.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">YouTube Videos (optional)</label>
                                    <p class="text-sm text-gray-500 mb-4">Add YouTube video links to showcase your mod.</p>
                                    <div id="youtube-videos-container" class="space-y-3">
                                        @php
                                            $oldVideos = old('youtube_videos', []);
                                            if (empty($oldVideos)) {
                                                $oldVideos = [''];
                                            }
                                        @endphp
                                        @foreach($oldVideos as $index => $video)
                                            <div class="flex gap-2 youtube-video-item">
                                                <input type="url" name="youtube_videos[]" value="{{ $video }}" 
                                                    class="flex-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-600 focus:ring-orange-600 sm:text-sm" 
                                                    placeholder="https://www.youtube.com/watch?v=...">
                                                @if($index > 0)
                                                    <button type="button" onclick="removeYoutubeVideo(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200 text-sm font-medium">Remove</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" onclick="addYoutubeVideo()" class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-medium">+ Add Another Video</button>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-900 mb-2">Mod Images</label>
                                    <p class="text-sm text-gray-500 mb-4">Upload multiple images for your mod. First image will be used as the main thumbnail.</p>
                                    
                                    <div class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 bg-gray-50 hover:border-orange-400 transition-colors cursor-pointer" onclick="document.getElementById('file-upload').click()">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                                <span class="font-semibold text-orange-600 hover:text-orange-500">Upload images</span>
                                                <input id="file-upload" name="images[]" type="file" class="hidden" multiple accept="image/*" onchange="previewImages(this)">
                                            </div>
                                             <p class="text-xs text-gray-500 mt-2" id="file-count">No files selected</p>
                                        </div>
                                    </div>
                                    <div id="image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 hidden"></div>
                                </div>
                            </div>

                        <script>
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
                                            // First image is main (if new upload session, we just show order)
                                            const isMain = index === 0;
                                            div.innerHTML = `
                                                <div class="relative">
                                                    ${isMain ? '<span class="absolute top-2 left-2 bg-orange-600 text-white text-xs px-2 py-1 rounded z-10">Main</span>' : ''}
                                                    <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover rounded-lg border-2 ${isMain ? 'border-orange-500' : 'border-gray-300'}">
                                                </div>
                                                <div class="mt-2 text-xs text-gray-600">
                                                    <p class="truncate">${file.name}</p>
                                                </div>
                                            `;
                                            preview.appendChild(div);
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                }
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
                                const item = button.closest('.youtube-video-item');
                                const container = document.getElementById('youtube-videos-container');
                                if (container.children.length > 1) {
                                    item.remove();
                                }
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

                    @if(setting('recaptcha.enabled'))
                        <div class="space-y-6">
                            <div class="mt-4">
                                {!! NoCaptcha::display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="text-red-600 text-sm">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="pt-5 flex justify-end gap-x-3">
                        <button type="button" class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="rounded-md bg-orange-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Submit Mod</button>
                    </div>
                </form>
                </div>
                </div>
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
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            contextmenu: 'link image imagetools table',
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            branding: false
        });
    });
</script>
@endpush
@endsection

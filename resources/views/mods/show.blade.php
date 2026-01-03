@extends('layouts.app')

@section('title', $mod->title)
@section('meta_description', Str::limit(strip_tags($mod->description), 160))
@section('meta_image', $mod->first_image_url)

@section('content')
<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Home</span>
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('categories.show', $mod->category) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $mod->category->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500" aria-current="page">{{ $mod->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image Gallery -->
            <div class="flex flex-col">
                @php
                    $images = $mod->modImages;
                @endphp
                @if($images->count() > 0)
                    <!-- Main Image -->
                    <div class="relative w-full mb-4" x-data="{ currentImage: 0, images: @js($images->map(fn($img) => $img->url)->toArray()) }">
                        <div class="relative aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                            <img :src="images[currentImage]" :alt="$mod->title" class="w-full h-full object-cover cursor-pointer" @click="openLightbox(currentImage)">
                        </div>
                        
                        @if($images->count() > 1)
                            <!-- Navigation Arrows -->
                            <button @click="currentImage = (currentImage - 1 + images.length) % images.length" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="currentImage = (currentImage + 1) % images.length" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <!-- Thumbnails -->
                            <div class="mt-4 flex space-x-2 overflow-x-auto pb-2">
                                <template x-for="(image, index) in images" :key="index">
                                    <button @click="currentImage = index" class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition" :class="currentImage === index ? 'border-primary-orange' : 'border-gray-300'">
                                        <img :src="image" :alt="'Thumbnail ' + (index + 1)" class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="relative aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
                        <img src="https://placehold.co/800x600?text=No+Image" alt="{{ $mod->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>

            <!-- Mod Info -->
            <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $mod->title }}</h1>
                    @livewire('follow-toggle', ['model' => $mod])
                </div>
                
                <div class="mt-3 flex items-center space-x-2">
                    <h2 class="sr-only">Mod Status</h2>
                     @if($mod->user->is_verified)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" title="Verified Author">
                            <svg class="mr-1.5 h-3 w-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified Author
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Top Rated
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="space-y-6 text-base text-gray-700">
                        {!! app(App\Services\MarkdownService::class)->parse($mod->description) !!}
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Game Version</dt>
                            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $mod->latestVersion?->game_version ?? 'Not specified' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">File Size</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mod->latestVersion?->file_size ?? 'Unknown' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Uploaded By</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                {{ $mod->user->name }}
                                @if($mod->user->is_verified)
                                    <svg class="ml-1 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mod->published_at ? $mod->published_at->format('M d, Y') : $mod->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-10 flex flex-col space-y-4">
                    @if($mod->latestVersion && ($mod->latestVersion->download_url || $mod->download_url))
                        <a href="{{ route('mods.download', $mod) }}" class="flex items-center justify-center rounded-md border border-transparent bg-primary-orange py-4 px-8 text-lg font-bold text-white shadow hover:bg-primary-orange-dark focus:outline-none focus:ring-2 focus:ring-primary-orange-light focus:ring-offset-2 transform transition hover:scale-105">
                             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download v{{ $mod->latestVersion->version_number }}
                        </a>
                    @else
                        <button disabled class="flex items-center justify-center rounded-md border border-transparent bg-gray-400 py-3 px-8 text-base font-medium text-white cursor-not-allowed">
                            Download Unavailable
                        </button>
                    @endif
                    
                    <!-- Version History -->
                    @if($mod->versions->count() > 1)
                    <div class="mt-6" x-data="{ showHistory: false }">
                        <button @click="showHistory = !showHistory" class="text-sm text-primary-orange hover:text-primary-orange-dark font-medium flex items-center">
                            <span x-text="showHistory ? 'Hide Version History' : 'Show Version History'"></span>
                            <svg class="ml-1 h-4 w-4" :class="{'rotate-180': showHistory}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="showHistory" class="mt-2 border-t border-gray-200">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach($mod->versions as $version)
                                    <li class="py-3 flex justify-between items-center text-sm">
                                        <div class="flex flex-col">
                                            <span class="font-medium">v{{ $version->version_number }}</span>
                                            <span class="text-gray-500">{{ $version->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="text-gray-500">{{ $version->game_version }}</div>
                                        @if($version->id !== $mod->latestVersion->id)
                                             <a href="#" class="text-gray-400 text-xs hover:text-gray-600">Download</a>
                                        @else
                                            <span class="text-green-600 text-xs font-bold">Latest</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <button type="button" class="flex items-center justify-center rounded-md py-2 px-3 text-gray-400 hover:bg-gray-100 hover:text-gray-500" title="Report this mod" onclick="document.getElementById('report-modal').classList.remove('hidden')">
                         <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="ml-2 text-sm">Report Issue</span>
                    </button>
                    
                    <x-ad-slot name="mod_detail_bottom" />
                </div>
            </div>
        </div>
        
        <!-- Ratings & Comments Grid -->
        <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Ratings -->
            <div class="lg:col-span-1">
                @livewire('mod-rating-form', ['mod' => $mod])
            </div>
            
            <!-- Right Column: Comments -->
            <div class="lg:col-span-2">
                @livewire('mod-comments', ['mod' => $mod])
            </div>
        </div>

        <!-- Similar Mods -->
        @if($similar->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Similar Mods</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                     @foreach($similar as $sim)
                        <div class="group relative">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                 <img src="{{ $sim->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $sim->title }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('mods.show', $sim) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $sim->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $sim->category->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Report Modal -->
<div id="report-modal" class="hidden fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('report-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 relative z-50">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Report Mod</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Please provide a reason for reporting this mod. We will review it shortly.
                        </p>
                    </div>
                </div>
            </div>
            <form action="{{ route('reports.store') }}" method="POST" class="mt-5 sm:mt-6">
                @csrf
                <input type="hidden" name="reportable_type" value="App\Models\Mod">
                <input type="hidden" name="reportable_id" value="{{ $mod->id }}">
                
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 text-left">Reason</label>
                    <select id="reason" name="reason" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border p-2" required>
                        <option value="Broken Link">Broken Link</option>
                        <option value="Inappropriate Content">Inappropriate Content</option>
                        <option value="Copyright Violation">Copyright Violation</option>
                        <option value="Malware/Virus">Malware/Virus</option>
                        <option value="Spam">Spam</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 text-left">Additional Details</label>
                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2"></textarea>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        Submit Report
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm" onclick="document.getElementById('report-modal').classList.add('hidden')">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center" onclick="this.classList.add('hidden')">
    <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300" onclick="document.getElementById('lightbox').classList.add('hidden')">&times;</button>
    <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
    <button class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-4xl hover:text-gray-300" onclick="event.stopPropagation(); previousImage()">&#8249;</button>
    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-4xl hover:text-gray-300" onclick="event.stopPropagation(); nextImage()">&#8250;</button>
</div>

<script>
    @if($images->count() > 0)
    const lightboxImages = @js($images->map(fn($img) => $img->url)->toArray());
    let currentLightboxIndex = 0;
    
    window.openLightbox = function(index) {
        currentLightboxIndex = index;
        document.getElementById('lightbox-image').src = lightboxImages[index];
        document.getElementById('lightbox').classList.remove('hidden');
    };
    
    window.nextImage = function() {
        currentLightboxIndex = (currentLightboxIndex + 1) % lightboxImages.length;
        document.getElementById('lightbox-image').src = lightboxImages[currentLightboxIndex];
    };
    
    window.previousImage = function() {
        currentLightboxIndex = (currentLightboxIndex - 1 + lightboxImages.length) % lightboxImages.length;
        document.getElementById('lightbox-image').src = lightboxImages[currentLightboxIndex];
    };
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox.classList.contains('hidden')) {
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') previousImage();
            if (e.key === 'Escape') lightbox.classList.add('hidden');
        }
    });
    @endif
</script>
@endsection

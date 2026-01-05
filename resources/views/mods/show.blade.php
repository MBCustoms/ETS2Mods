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
                    <div class="relative w-full mb-4" x-data='{ 
                        currentImage: 0, 
                        images: @json($images->map(fn($img) => $img->url)->toArray()),
                        modTitle: @json($mod->title)
                    }'>
                        <div class="relative aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                            <img :src="images[currentImage]" :alt="modTitle" class="w-full h-full object-cover cursor-pointer" @click="openLightbox(currentImage)" loading="eager" width="800" height="450">
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
                                        <img :src="image" :alt="'Thumbnail ' + (index + 1)" class="w-full h-full object-cover" loading="lazy" width="80" height="80">
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
                
                <!-- Social Share Buttons -->
                <div class="mt-4 flex flex-wrap items-center gap-2" x-data='{
                    copied: false,
                    shareUrl: @json(route('mods.show', $mod)),
                    shareTitle: @json($mod->title),
                    shareText: @json(Str::limit(strip_tags($mod->description), 100)),
                    
                    async shareMod() {
                        if (navigator.share) {
                            try {
                                await navigator.share({
                                    title: this.shareTitle,
                                    text: this.shareText,
                                    url: this.shareUrl
                                });
                            } catch (err) {
                                console.log("Share cancelled");
                            }
                        }
                    },
                    
                    async copyLink() {
                        try {
                            await navigator.clipboard.writeText(this.shareUrl);
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        } catch (err) {
                            console.error("Failed to copy");
                        }
                    }
                }'>
                    <span class="text-sm font-medium text-gray-500">Share:</span>
                    
                    <!-- Native Share (Mobile) -->
                    <button @click="shareMod()" x-show="navigator.share" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                        </svg>
                        Share
                    </button>
                    
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('mods.show', $mod)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition" title="Share on Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    
                    <!-- Twitter/X -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('mods.show', $mod)) }}&text={{ urlencode($mod->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-blue-50 hover:border-blue-400 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition" title="Share on Twitter/X">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text={{ urlencode($mod->title . ' - ' . route('mods.show', $mod)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-green-50 hover:border-green-300 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition" title="Share on WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </a>
                    
                    <!-- Copy Link -->
                    <button @click="copyLink()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition" title="Copy link">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span x-text="copied ? 'Copied!' : 'Copy Link'">Copy Link</span>
                    </button>
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
                    <div class="prose prose-sm text-gray-500">
                        {!! $mod->description !!}
                    </div>
                </div>

                @if(!empty($mod->youtube_videos))
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Videos</h3>
                        <div class="space-y-6">
                            @foreach($mod->youtube_videos as $video)
                                <div class="aspect-video">
                                    <iframe src="{{ $mod->getYoutubeEmbedUrl($video) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg shadow-sm"></iframe>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

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
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <a href="{{ route('users.show', $mod->user) }}" class="hover:text-orange-600">{{ $mod->user->name }}</a>
                                    @if($mod->user->is_verified)
                                        <svg class="ml-1 h-4 w-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                                @if(!empty($mod->user->social_links))
                                    <div class="mt-2 flex items-center space-x-2">
                                        @if(!empty($mod->user->social_links['facebook']))
                                            <a href="{{ $mod->user->social_links['facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-600 transition" title="Facebook">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                            </a>
                                        @endif
                                        @if(!empty($mod->user->social_links['twitter']))
                                            <a href="{{ $mod->user->social_links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-400 transition" title="Twitter / X">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                            </a>
                                        @endif
                                        @if(!empty($mod->user->social_links['instagram']))
                                            <a href="{{ $mod->user->social_links['instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-pink-600 transition" title="Instagram">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                            </a>
                                        @endif
                                        @if(!empty($mod->user->social_links['youtube']))
                                            <a href="{{ $mod->user->social_links['youtube'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-red-600 transition" title="YouTube">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                            </a>
                                        @endif
                                        @if(!empty($mod->user->social_links['website']))
                                            <a href="{{ $mod->user->social_links['website'] }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-primary-orange transition" title="Website">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                            </a>
                                        @endif
                                    </div>
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
                    <!-- Main Download Button -->
                    @if($mod->download_url)
                        <a href="{{ route('mods.download', $mod) }}" class="flex items-center justify-center rounded-md border border-transparent bg-orange-600 py-4 px-8 text-lg font-bold text-white shadow hover:bg-primary-orange-dark focus:outline-none focus:ring-2 focus:ring-primary-orange-light focus:ring-offset-2 transform transition hover:scale-105">
                             <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Mod
                        </a>
                    @endif

                    <!-- Alternative Download Links -->
                    @if(!empty($mod->download_links))
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Alternative Mirrors</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($mod->download_links as $index => $link)
                                    <a href="{{ route('mods.download', ['mod' => $mod, 'index' => $index]) }}" class="flex items-center justify-center rounded-md border border-gray-300 bg-white py-3 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                        {{ $link['label'] ?? 'Mirror ' . ($index + 1) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Ad Unit -->
                    @if(!$mod->latestVersion && !$mod->download_url && empty($mod->download_links))
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

                    <button type="button" class="flex items-center justify-center rounded-md py-2 px-3 bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-500 transition duration-150 ease-in-out" title="Report this mod" onclick="document.getElementById('report-modal').classList.remove('hidden')">
                         <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="ml-2 text-sm">Report Issue</span>
                    </button>
                    
                    <x-ad-slot slotName="mod_detail_sidebar" />
                </div>
            </div>
        </div>
        
        <!-- Reviews & Comments -->
        <div class="mt-16">
            @livewire('mod-comments', ['mod' => $mod])
        </div>

        <!-- Similar Mods -->
        @if($similar->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Similar Mods</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                     @foreach($similar as $sim)
                        <div class="group relative">
                            <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
                                 <img src="{{ $sim->first_image_url ?: 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $sim->title }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full" loading="lazy" width="600" height="400">
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
                    <select id="reason" name="reason" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md border p-2" required>
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
                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2"></textarea>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                        Submit Report
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:col-start-1 sm:text-sm" onclick="document.getElementById('report-modal').classList.add('hidden')">
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

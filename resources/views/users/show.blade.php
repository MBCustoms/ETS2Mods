@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Profile Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-primary-orange">
            <div class="md:flex">
                <!-- Left Sidebar (Avatar & Bio) -->
                <div class="md:w-1/3 bg-gray-50 p-8 text-center border-r border-gray-100">
                    <div class="relative inline-block">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover ring-4 ring-white shadow-sm">
                        @else
                            <span class="inline-flex items-center justify-center h-32 w-32 rounded-full bg-gray-300 ring-4 ring-white shadow-sm text-4xl font-bold text-gray-500">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        @endif
                        @if($user->is_verified)
                        <span class="absolute bottom-1 right-1 bg-blue-500 p-1.5 rounded-full border-2 border-white text-white" title="Verified Author">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                        @endif
                    </div>
                    
                    <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-500">Joined {{ $user->created_at->format('M Y') }}</p>

                    <!-- Badges -->
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        @foreach($badges as $badge)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $badge['color'] ?? 'gray' }}-100 text-{{ $badge['color'] ?? 'gray' }}-800 border border-{{ $badge['color'] ?? 'gray' }}-200">
                                {{ $badge['name'] }}
                            </span>
                        @endforeach
                    </div>
                    
                    @if($user->bio)
                        <div class="mt-6 text-sm text-gray-600 italic">
                            "{{ $user->bio }}"
                        </div>
                    @endif
                    
                    <!-- Social Links -->
                    @if(!empty($user->social_links))
                        <div class="mt-6 flex justify-center space-x-4">
                            @foreach($user->social_links as $platform => $link)
                                <a href="{{ $link }}" target="_blank" class="text-gray-400 hover:text-primary-orange transition">
                                    <span class="sr-only">{{ ucfirst($platform) }}</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769 1.079-2.692 3.35-2.692.886 0 1.781.074 1.781.074v1.926z"/></svg> <!-- Generic Social Icon for demo -->
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right Content (Stats & Mods) -->
                <div class="md:w-2/3 p-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-3 gap-4 mb-8 text-center divide-x divide-gray-200">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_downloads']) }}</div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Total Downloads</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['mods_count'] }}</div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Mods Uploaded</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_rating'], 1) }} <span class="text-yellow-400 text-lg">★</span></div>
                            <div class="text-xs uppercase tracking-wide text-gray-500 mt-1">Avg Rating</div>
                        </div>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Published Mods</h2>
                    
                    @if($mods->count() > 0)
                        <div class="space-y-4">
                            @foreach($mods as $mod)
                                <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition border border-gray-100">
                                    <img src="{{ $mod->first_image_url ?: 'https://placehold.co/100x100?text=Mod' }}" class="w-16 h-16 rounded-md object-cover bg-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-bold text-gray-900 truncate">
                                            <a href="{{ route('mods.show', $mod) }}" class="hover:underline">{{ $mod->title }}</a>
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($mod->description), 80) }}</p>
                                        <div class="flex items-center mt-2 text-xs text-gray-400 space-x-3">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                {{ number_format($mod->downloads_count) }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                {{ number_format($mod->rating_avg_rating, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                         <a href="{{ route('mods.download', $mod) }}" class="text-xs font-medium text-primary-orange hover:text-primary-orange-dark border border-primary-orange px-3 py-1 rounded-full hover:bg-orange-50 transition">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $mods->links() }}
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            <p>No mods published yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

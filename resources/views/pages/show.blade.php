@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <div class="lg:w-3/4">
                <article class="bg-white rounded-lg shadow-sm p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
                    
                    <div class="prose prose-lg max-w-none">
                        {!! app(\App\Services\MarkdownService::class)->parse($page->content) !!}
                    </div>

                    @if($page->updated_at)
                        <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
                            Last updated: {{ $page->updated_at->format('F j, Y') }}
                        </div>
                    @endif
                </article>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/4 mt-8 lg:mt-0">
                <div class="sticky top-6">
                    <x-ad-slot slotName="page_sidebar" />
                </div>
            </div>
        </div>
    </div>
@endsection


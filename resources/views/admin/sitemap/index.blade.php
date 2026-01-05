@extends('layouts.admin')

@section('header', 'Sitemap Generator')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Sitemap Status</h3>
                <p class="mt-1 text-sm text-gray-500">Manage your XML sitemap.</p>
            </div>
            
            <form action="{{ route('admin.sitemap.generate') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Generate Nuw
                </button>
            </form>
        </div>

        <div class="border-t border-gray-200 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($exists)
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Generated</span>
                        @else
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Missing</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Last Generated</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $lastGenerated ? date('F j, Y g:i A', $lastGenerated) : 'Never' }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Sitemap URL</dt>
                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                        <a href="{{ $url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-2">{{ $url }}</a>
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </dd>
                </div>
                
                <div class="sm:col-span-2 bg-gray-50 p-4 rounded-md">
                    <dt class="text-sm font-medium text-gray-700 mb-2">Cron Job URL</dt>
                    <dd class="text-sm text-gray-600">
                        <p class="mb-2">Use this URL to automatically generate the sitemap via a cron job:</p>
                        <code class="block bg-gray-100 p-2 rounded border border-gray-200 select-all font-mono text-xs break-all">
                            {{ $cronUrl }}
                        </code>
                        <p class="mt-2 text-xs text-gray-500">Example: <code>curl -s "{{ $cronUrl }}"</code></p>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection

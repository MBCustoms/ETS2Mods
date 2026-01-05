@extends('layouts.installer')

@section('title', 'Server Requirements')

@section('content')
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Server Requirements</h3>
    
    <div class="space-y-4">
        <div class="flex items-center justify-between py-3 border-b border-gray-200">
            <span class="text-sm font-medium text-gray-700">PHP Version >= 8.3.0</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $requirements['php']['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $requirements['php']['current'] }}
            </span>
        </div>

        <h4 class="text-md font-medium text-gray-900 mt-6 mb-2">PHP Extensions</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($requirements['extensions'] as $ext => $enabled)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">{{ strtoupper($ext) }}</span>
                    @if($enabled)
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @else
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('footer')
    <div></div>
    @if($allMet)
        <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Next: Permissions
            <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @else
        <button disabled class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gray-400 cursor-not-allowed">
            Please fix requirements
        </button>
    @endif
@endsection

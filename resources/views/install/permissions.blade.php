@extends('layouts.installer')

@section('title', 'Permissions')

@section('content')
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Directory Permissions</h3>
    
    <div class="space-y-4">
        @foreach($permissions as $perm)
            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-700">{{ $perm['folder'] }}</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $perm['is_writable'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $perm['is_writable'] ? 'Writable' : 'Not Writable' }}
                </span>
            </div>
        @endforeach
    </div>

    @if(!$allWritable)
        <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Please set writable permissions (chmod 775) to the directories listed above.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('footer')
    <div></div>
    @if($allWritable)
        <a href="{{ route('install.environment') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Next: Environment
            <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
    @else
        <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Reload
        </a>
    @endif
@endsection

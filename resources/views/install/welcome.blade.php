@extends('layouts.installer')

@section('title', 'Welcome')

@section('content')
    <div class="text-center py-8">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-orange-100 mb-6">
            <svg class="h-12 w-12 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-4">Welcome to ETS2Mods Installer</h2>
        
        <p class="text-gray-600 max-w-xl mx-auto mb-6">
            Thank you for choosing ETS2Mods! This wizard will guide you through the installation process.
            We will check your server requirements, configure the database, and set up your admin account.
        </p>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-left max-w-2xl mx-auto">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Please ensure you have your database credentials ready before proceeding.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div></div> <!-- Spacer -->
    <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
        Start Installation
        <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </a>
@endsection
